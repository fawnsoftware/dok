<?php

namespace App\Markdown\ComponentBlock;

use App\Markdown\Util\AttributeHelper;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\StringContainerInterface;

final class ComponentBlock extends AbstractBlock implements StringContainerInterface
{
    private string $componentName;

    private string $modifier;

    private array $attrs;

    private int $length;

    private int $offset;

    private string $type;

    private string $componentView;

    private array $slots;

    public function __construct(
        string $componentName,
        string $modifier,
        string $attrs,
        int $length,
        int $offset,
        string $type,
        string $componentView,
        array $slots
    ) {
        parent::__construct();

        $this->componentName = $componentName;
        $this->modifier = $modifier;
        $this->setAttrs($attrs);
        $this->length = $length;
        $this->offset = $offset;
        $this->type = $type;
        $this->componentView = $componentView;
        $this->slots = $slots;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSlotName(): ?string
    {
        return $this->modifier ?? null;
    }

    public function getComponentName(): ?string
    {
        return $this->componentName ?? null;
    }

    public function getModifier(): string
    {
        return $this->modifier;
    }

    public function getComponentView(): ?string
    {
        return $this->componentView;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getAllowedSlots(): array
    {
        return $this->slots;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isSlot(): bool
    {
        return $this->type == 'slot';
    }

    public function isComponent(): bool
    {
        return $this->type == 'component';
    }

    public function setAttrs(string $attrs): void
    {
        $this->attrs = AttributeHelper::parseBlockAttributes($attrs);
    }

    public function getAttrs(): array
    {
        return $this->attrs;
    }

    public function getAttr(string $key, $default = null)
    {
        return $this->attrs[$key] ?? $default;
    }

    public function setLiteral(string $literal): void
    {
        $this->literal = $literal;
    }

    public function getLiteral(): string
    {
        return $this->literal;
    }
}
