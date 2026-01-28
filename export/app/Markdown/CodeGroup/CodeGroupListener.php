<?php

namespace App\Markdown\CodeGroup;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

class CodeGroupListener
{
    public function __invoke(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($walkEvent = $walker->next()) {
            $node = $walkEvent->getNode();
            $attrString = '';

            if (! $walkEvent->isEntering()) {
                continue;
            }

            if ($this->isInsideCodeGroup($node)) {
                continue;
            }

            if (! $node instanceof FencedCode) {
                continue;
            }

            if ($node->previous() instanceof \League\CommonMark\Extension\Attributes\Node\Attributes) {

                // If we shouldnt have this as a codegroup, continue
                if (isset($node->previous()->getAttributes()['codegroup']) && $node->previous()->getAttributes()['codegroup'] == 'false') {
                    continue;
                }

                // Otherwise, create stringified attributes
                $attrs = '';

                foreach ($node->previous()->getAttributes() as $key => $value) {
                    $attrs .= $key.'="'.$value.'" ';
                }

                $attrString = trim($attrs);
            }

            $group = new CodeGroup($attrString, $node->getLength(), $node->getOffset());

            // Gotta replace the node first before appending the child
            $node->replaceWith($group);
            $group->appendChild($node);
        }
    }

    private function isInsideCodeGroup($node): bool
    {
        return $node instanceof \App\Markdown\CodeGroup\CodeGroup
            || $node->parent() instanceof \App\Markdown\CodeGroup\CodeGroup
            || $this->hasCodeGroupAncestor($node);
    }

    private function hasCodeGroupAncestor($node): bool
    {
        $p = $node->parent();
        while ($p) {
            if ($p instanceof \App\Markdown\CodeGroup\CodeGroup) {
                return true;
            }
            $p = $p->parent();
        }

        return false;
    }
}
