<?php

namespace App\Markdown\HeadingWrap;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;

class HeadingWrapListener
{
    public function __invoke(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($walkEvent = $walker->next()) {
            $node = $walkEvent->getNode();

            if (! $walkEvent->isEntering()) {
                continue;
            }

            if (! $node instanceof Heading) {
                continue;
            }

            $children = $node->children();
            $heading = new HeadingWrap;

            foreach ($children as $child) {
                $heading->appendChild($child);
            }

            $node->detachChildren();
            $node->appendChild($heading);

        }
    }
}
