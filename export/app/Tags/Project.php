<?php

namespace App\Tags;

use Exception;
use Statamic\Exceptions\CollectionNotFoundException;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Site;
use Statamic\Tags\TagNotFoundException;
use Statamic\Tags\Tags;

class Project extends Tags
{
    public function index()
    {
        return [];
    }

    public function wildcard(string $tag)
    {
        $parts = explode(':', $tag);

        [$first, $second, $third] = array_pad($parts, 3, null);

        if ($first && ! isset($second)) {
            $method = $first;
        }

        if ($first == 'entry') {
            $method = 'entry';
            $wildcard = $second;
        }

        if ($first == 'stable' && $second == 'entry') {
            $method = 'stableEntry';
            $wildcard = $third;
        }

        if ($first == 'stable' && $second == 'url') {
            $method = 'stableUrl';
        }

        if (! isset($method)) {
            throw new TagNotFoundException("Tag {{ {$tag} }} not found");
        }

        if (! method_exists($this, $method)) {
            throw new TagNotFoundException("Cannot find method [{$method}]");
        }

        if (isset($wildcard)) {
            return $this->{$method}($wildcard);
        } else {
            return $this->{$method}();
        }
    }

    /**
     * {{ project:entry }} || {{ project:entry:* }}
     */
    public function entry(?string $wildcard = null)
    {
        if ($wildcard) {
            // Automatically returns null if nothing is found,
            // sending nothing back to the tag.
            return $this->project()->{$wildcard};
        }

        return $this->project();
    }

    /**
     * {{ project:stable:entry }} || {{ project:stable:entry:* }}
     */
    public function stableEntry(?string $wildcard = null)
    {
        $project = $this->project();

        if (! $project->latest_stable_release) {
            throw new Exception('You must have a latest stable release. Go to your project entry in the Releases collection and add one.');
        }

        if ($wildcard) {
            // Automatically returns null if nothing is found,
            // sending nothing back to the tag.
            return $project->latest_stable_release->{$wildcard};
        }

        return $project->latest_stable_release;
    }

    /**
     * {{ project:stable:url }}
     */
    public function stableUrl()
    {
        $project = $this->project();

        if (! $project->latest_stable_release) {
            throw new Exception('You must have a latest stable release. Go to your project entry in the Releases collection and add one.');
        }

        $collectionHandle = $project->latest_stable_release->version_collection->handle();

        $tree = Collection::findByHandle($collectionHandle)
            ->structure()
            ->in(Site::current()->handle())
            ->tree();

        throw_if(empty($tree), new Exception("The [{$collectionHandle}] collection structure has no pages yet."));

        return Entry::find($tree[0]['entry'])->url();
    }

    /**
     * {{ project:versions }}
     */
    public function versions()
    {
        $release = $this->releaseEntry($this->getCollectionHandle());

        $children = $release
            ->parent()
            ->flattenedPages()
            ->map(function ($page) {
                return $page->id();
            })
            ->toArray();

        $versions = collect($children)
            ->map(function ($child) {
                $release = Entry::find($child);
                $releaseCollection = Collection::find($release->get('version_collection'));
                $releaseCollectionHomeId = $releaseCollection?->structure()->in(Site::current()->handle())->tree()[0]['entry'];

                return [
                    'entry' => $release,
                    'version' => $release->version,
                    'url' => Entry::find($releaseCollectionHomeId)?->url(),
                    'label' => $release->label,
                ];

            })
            ->reverse()
            ->values()
            ->toArray();

        return $versions;
    }

    /**
     * {{ project:versioned }}
     */
    public function versioned()
    {
        $release = $this->releaseEntry($this->getCollectionHandle());

        $count = $release
            ->parent()
            ->flattenedPages()
            ->count();

        return $count > 1;
    }

    private function getCollectionHandle()
    {
        $collection = $this->params->get('collection') ?? $this->context->get('collection');

        // The context or param might be giving us an augmented
        // value, convert it to a string instead
        if ($collection instanceof \Statamic\Fields\Value) {
            $collection = $collection->handle;
        }

        // It might even be passed in as the collection entry
        // perhaps through the page:collection page context
        if ($collection instanceof \Statamic\Entries\Collection) {
            $collection = $collection->handle;
        }

        throw_if(! $collection, new Exception('Cannot find collection in context or tag params.'));

        throw_if(! Collection::findByHandle($collection), new CollectionNotFoundException($collection));

        return $collection;
    }

    private function project()
    {
        $release = $this->releaseEntry($this->getCollectionHandle());

        return Entry::findOrFail($release->parent()->id);
    }

    private function releaseEntry(string $collectionHandle): \Statamic\Entries\Entry
    {
        $release = Entry::query()
            ->where('collection', 'releases')
            ->where('version_collection', $collectionHandle)
            ->first();

        throw_if(! $release, new Exception("Cannot find a [releases] entry for collection [{$collectionHandle}]."));

        return $release;
    }
}
