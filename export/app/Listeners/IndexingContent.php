<?php

namespace App\Listeners;

use DOMDocument;
use DOMProcessingInstruction;
use DOMXPath;
use Stillat\DocumentationSearch\Events\IndexingContent as IndexingContentEvent;

class IndexingContent
{
    public function handle(IndexingContentEvent $event): void
    {
        $html = $event->value->value;

        $html = $this->stripTableOfContents($html);
        $html = $this->unwrapCodeGroups($html);
        $html = $this->stripH1($html);

        $event->value->value = $html;
    }

    private function stripTableOfContents(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $dom = $this->parseFragment($html);

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' table-of-contents ')]");

        foreach ($nodes as $node) {
            $node->parentNode?->removeChild($node);
        }

        return $dom->saveHTML();
    }

    private function unwrapCodeGroups(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $dom = $this->parseFragment($html);

        $xpath = new DOMXPath($dom);
        $groups = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' code-group ')]");

        foreach ($groups as $group) {
            $pres = $xpath->query('.//pre', $group);

            foreach ($pres as $pre) {
                $group->parentNode?->insertBefore($pre, $group);
            }

            $group->parentNode?->removeChild($group);
        }

        return $dom->saveHTML();
    }

    private function stripH1(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $dom = $this->parseFragment($html);

        $xpath = new DOMXPath($dom);
        $headings = $xpath->query('//h1');

        foreach ($headings as $heading) {
            $heading->parentNode?->removeChild($heading);
        }

        return $dom->saveHTML();
    }

    /**
     * Parse HTML as UTF-8, preserving the full document structure.
     *
     * DOMDocument::loadHTML() assumes ISO-8859-1 when no encoding is
     * declared, which mangles multi-byte characters (em dashes, curly
     * quotes, accents). The leading XML processing instruction forces
     * libxml to read the input as UTF-8; it's removed again afterwards
     * so it isn't serialized back out by saveHTML().
     *
     * Note: this must keep returning the full <html><body> document,
     * not just its inner content — Stillat's Splitter::split() re-parses
     * this value with LIBXML_HTML_NOIMPLIED, which requires that wrapper
     * to already be present (it's added upstream by Splitter::wrap()).
     * Stripping it silently drops nearly all indexed content.
     */
    private function parseFragment(string $html): DOMDocument
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        foreach (iterator_to_array($dom->childNodes) as $node) {
            if ($node instanceof DOMProcessingInstruction) {
                $dom->removeChild($node);
            }
        }

        return $dom;
    }
}
