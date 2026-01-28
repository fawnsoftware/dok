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
     * @param  bool  $cleanDirectory  Whether to clean the directory before syncing.
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

        // Clean directory for a true 1:1 synd
        File::cleanDirectory($this->destination);

        $stats = [
            'files' => 0,
            'directories' => 0,
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
            } elseif ($item['type'] === 'tree') {
                $this->ensureDirectory($item['path']);
                $stats['directories']++;
            }
        }

        return $stats;
    }

    /**
     * Load the resource configuration from config.
     *
     * @param  string  $name  The resource name.
     * @return array The resource configuration.
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
     * @return array The repository tree.
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
     * Download a blob from GitHub and save it locally.
     *
     * @param  string  $filePath  The path to the file in the repository.
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
        $localFilePath = Str::finish($this->destination, '/').$filePath;

        File::ensureDirectoryExists(dirname($localFilePath));
        File::put($localFilePath, $content);
    }

    /**
     * Ensure a directory exists.
     *
     * @param  string  $path  The relative path to the directory.
     */
    protected function ensureDirectory(string $path): void
    {
        $directory = Str::finish($this->destination, '/').$path;
        File::ensureDirectoryExists($directory);
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
