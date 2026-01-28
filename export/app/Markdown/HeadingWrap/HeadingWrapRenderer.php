<?php

namespace App\Markdown\HeadingWrap;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class HeadingWrapRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        return '<span class="heading-content">'.$childRenderer->renderNodes($node->children()).'</span>';
    }
}
