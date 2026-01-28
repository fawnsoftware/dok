<?php

namespace App\Markdown\TableWrap;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Table\Table;

class TableWrapListener
{
    public function __invoke(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($walkEvent = $walker->next()) {
            $node = $walkEvent->getNode();

            if (! $walkEvent->isEntering()) {
                continue;
            }

            if (! $node instanceof Table) {
                continue;
            }

            $table = new TableWrap;

            $node->replaceWith($table);
            $table->appendChild($node);
        }
    }
}
