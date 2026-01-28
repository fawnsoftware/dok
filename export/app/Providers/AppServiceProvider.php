<?php

namespace App\Providers;

use App\Http\Controllers\SyncRemoteController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Markdown;
use Statamic\Facades\Utility;
use Statamic\Statamic;

class AppServiceProvider extends ServiceProvider
{
    protected string $highlightTheme = 'olaolu-palenight';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Statamic::vite('app', [
            'input' => [
                'resources/js/cp/index.js',
                'resources/css/cp.css',
            ],
            'buildDirectory' => 'vendor/app',
        ]);

        $this->registerMarkdownExtensions();
        $this->registerTorchlightEngine();
        $this->registerUtilities();
        $this->registerComputedContent();
    }

    protected function registerMarkdownExtensions(): void
    {
        Markdown::addExtensions(function () {
            return [
                new \App\Markdown\TableWrap\TableWrapExtension,
                // new \App\Markdown\Blade\BladeExtension,
                new \League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension,
                new \League\CommonMark\Extension\TableOfContents\TableOfContentsExtension,
                new \App\Markdown\ComponentBlock\ComponentBlockExtension,
                new \App\Markdown\CodeGroup\CodeGroupExtension,
                new \App\Markdown\HeadingWrap\HeadingWrapExtension,
            ];
        });
    }

    protected function registerTorchlightEngine(): void
    {
        if (! config('dok.markdown.torchlight.enabled') || ! class_exists(\Torchlight\Engine\CommonMark\Extension::class)) {
            return;
        }

        \Torchlight\Engine\CommonMark\Extension::setThemeResolver(function () {
            return config('dok.markdown.torchlight.theme');
        });

        \Torchlight\Engine\Options::setDefaultOptionsBuilder(function () {
            return \Torchlight\Engine\Options::fromArray(config('dok.markdown.torchlight.options'));
        });

        Markdown::addExtension(function () {
            return new \Torchlight\Engine\CommonMark\Extension;
        });

    }

    protected function registerUtilities(): void
    {
        Utility::extend(function () {
            Utility::register('remote_sync')
                ->view('cp.remote_sync')
                ->title('Remote Sync')
                ->navTitle('Remote Sync')
                ->icon('sync')
                ->description('Sync documentation from remote locations.')
                ->routes(function ($router) {
                    $router->post('/', [SyncRemoteController::class, 'sync'])->name('sync');
                });
        });
    }

    protected function registerComputedContent(): void
    {
        $collections = Collection::all()->pluck('handle')->toArray();

        Collection::computed($collections, 'synced_content', function ($entry, $value) {
            return $this->getContentResource($entry);
        });

        Collection::computed($collections, 'content', function ($entry, $value) {
            return $value ?? $this->getContentResource($entry);
        });
    }

    protected function getContentResource($entry)
    {

        // Because this uses Entry::find($id) under the hood,
        // only refresh if it actually has an id. We might
        // be creating a new entry where an ID is not set
        if ($entry->id) {
            $entry = $entry->fresh();
        }

        if (! $entry->get('resource_location')) {
            // This cant return anything otherwise it'll put the content variable into the
            // frontmatter and thows a YAML error because you can't have a content
            // variable in the frontmatter whilst there is a document body.
            return;
        }

        $file = Str::finish(config('dok.resource_location'), '/').$entry->get('resource_location');

        if (File::exists($file)) {
            return file_get_contents($file);
        }
    }
}
