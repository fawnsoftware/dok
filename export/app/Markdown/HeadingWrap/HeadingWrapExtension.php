<?php

namespace App\Markdown\HeadingWrap;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class HeadingWrapExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(HeadingWrap::class, new HeadingWrapRenderer);

        $environment->addEventListener(
            \League\CommonMark\Event\DocumentParsedEvent::class,
            new HeadingWrapListener
        );
    }
}
