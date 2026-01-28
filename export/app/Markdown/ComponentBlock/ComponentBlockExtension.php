<?php

namespace App\Markdown\ComponentBlock;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class ComponentBlockExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new ComponentBlockStartParser)
            ->addRenderer(ComponentBlock::class, new ComponentBlockRenderer);
    }
}
