<?php

namespace App\Markdown\TableWrap;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class TableWrapExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(TableWrap::class, new TableWrapRenderer);

        $environment->addEventListener(
            \League\CommonMark\Event\DocumentParsedEvent::class,
            new TableWrapListener
        );
    }
}
