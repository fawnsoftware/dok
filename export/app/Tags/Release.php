<?php

namespace App\Tags;

use Exception;
use Illuminate\Support\Facades\Log;
use Statamic\Exceptions\CollectionNotFoundException;
use Statamic\Exceptions\NavigationNotFoundException;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Nav;
use Statamic\Tags\TagNotFoundException;
use Statamic\Tags\Tags;

class Release extends Tags
{
    public function wildcard(string $tag)
    {
        $parts = explode(':', $tag);

        [$first, $second, $third] = array_pad($parts, 3, null);

        switch (true) {
            case $first && ! isset($second):
                $method = $first;
                break;

            case $first === 'entry':
                $method = 'entry';
                $wildcard = $second;
                break;

            case $first === 'nav' && $second === 'handle':
                $method = 'navHandle';
                break;
        }

        if (! method_exists($this, $method)) {
            throw new TagNotFoundException("Cannot find method [{$method}]");
        }

        if (isset($wildcard)) {
            return $this->{$method}($wildcard);
        } else {
            return $this->{$method}();
        }

        throw new TagNotFoundException("Tag {{ {$tag} }} not found");
    }

    /**
     * {{ release:entry }} || {{ release:entry:* }}
     */
    public function entry(?string $wildcard = null)
    {
        if ($wildcard) {
            // Automatically returns null if nothing is found,
            // sending nothing back to the tag.
            return $this->release()->{$wildcard};
        }

        return $this->release();
    }

    /**
     * {{ release:nav:handle }}
     */
    public function navHandle()
    {
        return $this->release()->version_navigation->handle;
    }

    /**
     * {{ release:outdated }}
     */
    public function outdated(): bool
    {
        return $this->release()->data()->get('show_outdated_banner') ? true : false;
    }

    /**
     * {{ release:breadcrumbs }}
     */
    public function breadcrumbs(): array
    {
        $handle = $this->navHandle();
        $nav = Nav::findByHandle($handle);
        $currentUri = '/'.request()->path();
        $breadcrumbs = [];

        if (! $nav) {
            throw new NavigationNotFoundException($handle);
        }

        // Might be a new collection without nav items yet
        if (! $nav->trees()->has('default')) {
            Log::warning("Navigation [{$handle}] does not have a [default] tree.");

            return [];
        }

        $items = $nav->trees()->get('default')->tree();
        $breadcrumbs = $this->findBreadcrumbTrail($items, $currentUri);

        if (empty($breadcrumbs)) {
            array_unshift($breadcrumbs, [
                'title' => $this->context->get('title'),
            ]);
        }

        if ($this->params->get('prefix') && (! $this->params->get('prefix_single') == true || count($breadcrumbs) === 1)) {
            array_unshift($breadcrumbs, ['title' => $this->params->get('prefix')]);
        }

        return $breadcrumbs;
    }

    /**
     * {{ release:version }}
     */
    public function version(): string
    {
        return $this->release()->version;
    }

    private function release(): \Statamic\Entries\Entry
    {
        return Entry::query()
            ->where('collection', 'releases')
            ->where('version_collection', $this->getCollectionHandle())
            ->first();
    }

    private function findBreadcrumbTrail(array $items, string $currentUri, array $trail = [])
    {
        $stack = [];

        foreach ($items as $item) {
            $stack[] = [$item, []];
        }

        while (! empty($stack)) {
            [$current, $trail] = array_pop($stack);

            $entryId = $current['entry'] ?? null;

            if ($entry = Entry::find($entryId)) {
                $node = [
                    'entry' => $entry,
                    'title' => $current['title'] ?? $entry->title,
                    'url' => $entry->uri,
                ];
            } else {
                $node = [
                    'title' => $current['title'] ?? null,
                    'url' => null,
                ];
            }

            $newTrail = [...$trail, $node];

            if ($node['url'] === $currentUri) {
                return $newTrail;
            }

            if (! empty($current['children'])) {
                foreach ($current['children'] as $child) {
                    $stack[] = [$child, $newTrail];
                }
            }
        }

        return [];
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
}
