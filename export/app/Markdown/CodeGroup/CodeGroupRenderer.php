<?php

namespace App\Markdown\CodeGroup;

use App\Markdown\Util\ComponentSlot;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class CodeGroupRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        CodeGroup::assertInstanceOf($node);

        // Get fenced code children
        $fencedCodeBlocks = array_values(
            array_filter($node->children(), fn ($child) => $child instanceof FencedCode)
        );

        $buttons = [];
        $panels = [];

        foreach ($fencedCodeBlocks as $index => $child) {
            $childrenHtml = (string) $childRenderer->renderNodes([$child]);
            $collapse = $this->shouldCollapse($child);

            $buttons[] = [
                'index' => $index,
                'title' => $node->getAttr('title', $this->getCodeTitle($child)),
            ];

            $panels[] = [
                'index' => $index,
                'html' => new ComponentSlot($childrenHtml),
                'collapse' => $collapse,
                'expandText' => __('Expand code'),
                'collapseText' => __('Collapse code'),
            ];
        }

        $rendered = view('components.code-group.code-group', [
            'buttons' => $buttons,
            'panels' => $panels,
            'single' => count($fencedCodeBlocks) === 1,
        ])->render();

        return $rendered;
    }

    private function shouldCollapse(FencedCode $code): bool
    {
        if ($code->data['attributes']['collapse'] ?? null) {
            return true;
        }

        return false;
    }

    private function getCodeTitle(FencedCode $code): string
    {
        if ($code->data['attributes']['title'] ?? null) {
            return htmlspecialchars($code->data['attributes']['title']);
        }

        if ($code->getInfo()) {
            return htmlspecialchars($code->getInfo());
        }

        return __('code');
    }
}
