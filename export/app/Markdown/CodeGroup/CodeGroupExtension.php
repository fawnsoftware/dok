<?php

namespace App\Markdown\CodeGroup;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

class CodeGroupExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('code_group', Expect::structure([
            'auto_group' => Expect::bool($default = false),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(new CodeGroupStartParser);
        $environment->addRenderer(CodeGroup::class, new CodeGroupRenderer);

        if ($environment->getConfiguration()->get('code_group/auto_group') === false) {
            return;
        }

        $environment->addEventListener(
            \League\CommonMark\Event\DocumentParsedEvent::class,
            new CodeGroupListener,
            100000
        );
    }
}
