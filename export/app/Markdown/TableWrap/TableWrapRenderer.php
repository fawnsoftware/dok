<?php

namespace App\Markdown\TableWrap;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TableWrapRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        return new HtmlElement(
            'div',
            ['class' => 'markdown-table-wrapper'],
            new HtmlElement('div', ['class' => 'markdown-table-wrapper-inner'],
                $childRenderer->renderNodes($node->children()))
        );
    }
}
