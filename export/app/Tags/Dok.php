<?php

namespace App\Tags;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalink;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\RawMarkupContainerInterface;
use League\CommonMark\Node\StringContainerHelper;
use League\CommonMark\Parser\MarkdownParser;
use Statamic\Tags\Tags;

class Dok extends Tags
{
    /**
     * The {{ dok }} tag.
     */
    public function index()
    {
        return [];
    }

    /**
     * {{ dok:outline }}
     */
    public function outline()
    {
        $min = $this->params->get('min') ?? 999999;

        // Gotta use raw Markdown, not preprocessed by Statamic
        $rawMarkdown = $this->params['markdown'] ?? '';

        // Create a fresh replica CommonMark environment
        $environment = new Environment(config('statamic.markdown.configs.default'));

        // Add core extensions and permalink support
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new HeadingPermalinkExtension);
        $environment->addExtension(new \League\CommonMark\Extension\TableOfContents\TableOfContentsExtension);
        $environment->addExtension(new \League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension);
        $environment->addExtension(new \League\CommonMark\Extension\Attributes\AttributesExtension);

        // Convert document
        $document = new MarkdownParser($environment)->parse($rawMarkdown);

        $headings = [];

        foreach ($this->getOutlineHeadingLinks($document) as $headingLink) {
            $heading = $headingLink->parent();

            // Make sure this is actually tied to a heading
            if (! $heading instanceof Heading) {
                continue;
            }

            $headings[] = [
                'text' => StringContainerHelper::getChildText($heading, [RawMarkupContainerInterface::class]),
                'level' => $heading->getLevel(),
                'id' => $headingLink->getSlug(),
            ];
        }

        $headingTree = $this->builtOutlineTree($headings);

        if (empty($headingTree) || count($headingTree) < $min) {
            return $this->parseNoResults();
        }

        return $this->aliasedResult($headingTree);
    }

    private function builtOutlineTree(array $items): array
    {
        $tree = [];
        $stack = [];

        foreach ($items as $item) {
            $item['children'] = [];

            // Pop until stack top has lower level than current
            while (! empty($stack) && $stack[array_key_last($stack)]['level'] >= $item['level']) {
                array_pop($stack);
            }

            if (empty($stack)) {
                // top level
                $tree[] = $item;
                $stack[] = &$tree[array_key_last($tree)];
            } else {
                // append to parent's children
                $parent = &$stack[array_key_last($stack)];
                $parent['children'][] = $item;
                $stack[] = &$parent['children'][array_key_last($parent['children'])];
            }
        }

        return $tree;
    }

    private function getOutlineHeadingLinks($document)
    {
        foreach ($document->iterator(NodeIterator::FLAG_BLOCKS_ONLY) as $node) {
            if (! $node instanceof Heading) {
                continue;
            }

            foreach ($node->children() as $child) {
                if ($child instanceof HeadingPermalink) {
                    yield $child;
                }
            }
        }
    }
}
