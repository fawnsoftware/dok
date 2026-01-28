<?php

namespace App\Markdown\ComponentBlock;

use App\Markdown\Util\ComponentSlot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlFilter;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class ComponentBlockRenderer implements ConfigurationAwareInterface, NodeRendererInterface
{
    private ConfigurationInterface $config;

    /**
     * @param  Card  $node
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {

        $view = collect();

        ComponentBlock::assertInstanceOf($node);

        if ($node->isSlot()) {
            return $childRenderer->renderNodes($node->children());
        }

        // For the default slot, we wanna get all of the children unless it's a slot because
        // we're rendering that separately and passing it to the blade view
        $content = collect($node->children())
            ->filter(function ($child) {
                if ($child instanceof ComponentBlock && $child->isSlot()) {
                    return false;
                }

                return true;
            })
            ->values()
            ->all();

        $view->put('slot', new ComponentSlot($childRenderer->renderNodes($content)));

        $slots = collect($node->children())
            ->filter(fn ($child) => $child instanceof ComponentBlock
                && $child->isSlot()
                && in_array($child->getSlotName(), $node->getAllowedSlots())
            )
            ->values()
            ->all();

        foreach ($slots as $slot) {
            if ($this->isReservedWord($slot->getModifier())) {
                $this->logReservedWord($slot->getModifier(), $node);

                continue;
            }

            $slotHtml = (string) $childRenderer->renderNodes([$slot]);
            $view->put(Str::camel($slot->getModifier()), new ComponentSlot($slotHtml));
        }

        foreach ($node->getAttrs() as $key => $value) {
            if ($this->isReservedWord($key)) {
                $this->logReservedWord($key, $node);

                continue;
            }

            $view->put(
                Str::camel($key),
                new ComponentSlot(HtmlFilter::filter($value, $this->config->get('html_input')))
            );
        }

        $view->put('name', $node->getComponentName());

        $html = view($node->getComponentView(), $view->all())->render();

        return $html;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    private function isReservedWord(string $word): bool
    {
        $reserved = [
            'data', 'render', 'resolve', 'resolveView',
            'shouldRender', 'view', 'withAttributes', 'withName',
        ];

        return in_array($word, $reserved);
    }

    private function logReservedWord($word, $node)
    {
        Log::warning('Reserved word being used in markdown component', [
            'word' => $word,
            'component' => $node->getComponentName(),
        ]);
    }
}
