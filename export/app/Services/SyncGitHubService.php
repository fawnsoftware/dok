<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncGitHubService
{
    protected string $resourceName;

    protected array $resource;

    protected string $destination;

    public function __construct() {}

    /**
     * Sync the GitHub repository to the local file system.
     *
     * @return array Statistics about the sync operation.
     *
     * @throws \Exception If the GitHub API request fails.
     */
    public function sync(string $resourceName): array
    {
        $this->resourceName = $resourceName;
        $this->resource = $this->loadResourceConfig($resourceName);

        $this->validateResource();
        $this->setDestination();

        $tree = $this->fetchRepositoryTree();

        // Clean content directory for a true 1:1 sync
        File::cleanDirectory($this->destination);

        $stats = [
            'files' => 0,
            'directories' => 0,
            'public_files' => 0,
            'skipped' => 0,
        ];

        foreach ($tree as $item) {
            // Skip items not in specified content paths
            if (isset($this->resource['content']) && ! Str::startsWith($item['path'], $this->resource['content'])) {
                $stats['skipped']++;

                continue;
            }

            if ($item['type'] === 'blob') {
                $this->downloadAndSaveBlob($item['path']);
                $stats['files']++;

                if ($this->isPublicPath($item['path'])) {
                    $stats['public_files']++;
                }
            } elseif ($item['type'] === 'tree') {
                $directory = Str::finish($this->destination, '/').$item['path'];
                File::ensureDirectoryExists($directory);

                $stats['directories']++;
            }
        }

        return $stats;
    }

    /**
     * Load the resource configuration from config.
     *
     * @throws \Exception If the resource doesn't exist.
     */
    protected function loadResourceConfig(string $name): array
    {
        if (! isset(config('dok.resources')[$name])) {
            throw new \Exception("Cannot find resource [{$name}]");
        }

        return config('dok.resources')[$name];
    }

    /**
     * Validate that all required resource configuration exists.
     *
     * @throws \Exception If required configuration is missing.
     */
    protected function validateResource(): void
    {
        if (empty($this->resource['token'])) {
            throw new \Exception("Missing [token] configuration for [{$this->resourceName}].");
        }

        if (empty($this->resource['repo'])) {
            throw new \Exception("Missing [repo] configuration for [{$this->resourceName}].");
        }

        if (empty($this->resource['branch'])) {
            throw new \Exception("Missing [branch] configuration for [{$this->resourceName}].");
        }
    }

    /**
     * Set the destination path for synced files.
     */
    protected function setDestination(): void
    {
        $this->destination = Str::finish(config('dok.resource_location'), '/').$this->resourceName;
    }

    /**
     * Fetch the repository tree from GitHub.
     *
     * @throws \Exception If the request fails.
     */
    protected function fetchRepositoryTree(): array
    {
        $url = "https://api.github.com/repos/{$this->resource['repo']}/git/trees/{$this->resource['branch']}?recursive=1";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'Bearer '.$this->resource['token'],
            'X-GitHub-Api-Version' => '2022-11-28',
        ])->get($url);

        if (! $response->successful()) {
            throw new \Exception('GitHub API request failed: '.$response->body());
        }

        return $response->json()['tree'] ?? [];
    }

    /**
     * Download a blob from GitHub and save it locally (or to /public if its
     * path matches one of the configured [public] mappings).
     *
     * @throws \Exception If the download fails.
     */
    protected function downloadAndSaveBlob(string $filePath): void
    {
        $url = "https://api.github.com/repos/{$this->resource['repo']}/contents/{$filePath}";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'Bearer '.$this->resource['token'],
            'X-GitHub-Api-Version' => '2022-11-28',
        ])->get($url);

        if (! $response->successful() || ! isset($response->json()['content'])) {
            throw new \Exception("Failed to download blob: {$filePath}");
        }

        $content = base64_decode($response->json()['content']);

        if ($this->isPublicPath($filePath)) {
            $publicFilePath = $this->publicPathFor($filePath);
            File::ensureDirectoryExists(dirname($publicFilePath));
            File::put($publicFilePath, $content);
        } else {
            $localFilePath = Str::finish($this->destination, '/').$filePath;
            File::ensureDirectoryExists(dirname($localFilePath));
            File::put($localFilePath, $content);
        }
    }

    /**
     * Determine whether a given repo-relative path matches one of the
     * resource's configured [public] mappings.
     */
    protected function isPublicPath(string $filePath): bool
    {
        return $this->resolvePublicMapping($filePath) !== null;
    }

    /**
     * Find the [public] mapping (pattern => target) that matches the given
     * repo-relative path, if any.
     *
     * Supports two forms:
     *  - Wildcard:     'dok/images/*' => target   - pushes folder CONTENTS,
     *                                                 stripping the matched prefix.
     *  - Non-wildcard: 'dok/images'   => target   - pushes the folder itself,
     *                                                 preserving the full path.
     *
     * @return array{pattern: string, target: string, wildcard: bool}|null
     */
    protected function resolvePublicMapping(string $filePath): ?array
    {
        if (empty($this->resource['public'])) {
            return null;
        }

        foreach ($this->resource['public'] as $pattern => $target) {

            // Normalize away any leading/trailing slashes so '/dok/images' and
            // 'dok/images' are treated identically. GitHub doesn't send back a leading slash.
            $pattern = trim($pattern, '/');

            $isWildcard = Str::contains($pattern, '*');

            if ($isWildcard) {
                if (Str::is($pattern, $filePath)) {
                    return [
                        'pattern' => rtrim(Str::before($pattern, '*'), '/'),
                        'target' => $target,
                        'wildcard' => true,
                    ];
                }

                continue;
            }

            // Non-wildcard: match the exact folder or anything nested under it,
            // guarding against partial name collisions (e.g. `images` vs `images-2`).
            if ($filePath === $pattern || Str::startsWith($filePath, Str::finish($pattern, '/'))) {
                return [
                    'pattern' => $pattern,
                    'target' => $target,
                    'wildcard' => false,
                ];
            }
        }

        return null;
    }

    /**
     * Resolve the public path for a given repo-relative file path, based on
     * its matched [public] mapping.
     */
    protected function publicPathFor(string $filePath): string
    {
        $mapping = $this->resolvePublicMapping($filePath);

        $target = Str::finish($mapping['target'], '/');

        if ($mapping['wildcard']) {
            // Wildcard: strip the matched prefix entirely, keep only what's inside it.
            // dok/images/* -> foo/bar.png  =>  {target}/foo/bar.png
            $relative = ltrim(Str::after($filePath, $mapping['pattern']), '/');

            return $target.$relative;
        }

        // Non-wildcard: strip the prefix but keep the folder's own name as the new root.
        // dok/images -> foo/bar.png  =>  images/foo/bar.png  =>  {target}/images/foo/bar.png
        $folderName = Str::afterLast($mapping['pattern'], '/');
        $remainder = ltrim(Str::after($filePath, $mapping['pattern']), '/');

        $relative = $remainder !== ''
            ? $folderName.'/'.$remainder
            : $folderName;

        return $target.$relative;
    }

    /**
     * Get the resource name.
     */
    protected function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * Get the destination path.
     */
    protected function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * Get the repository information.
     */
    protected function getResourceInfo(): array
    {
        return [
            'name' => $this->resourceName,
            'repo' => $this->resource['repo'],
            'branch' => $this->resource['branch'],
            'destination' => $this->destination,
        ];
    }
}
