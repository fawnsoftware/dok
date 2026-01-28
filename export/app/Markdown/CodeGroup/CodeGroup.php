<?php

namespace App\Markdown\CodeGroup;

use App\Markdown\Util\AttributeHelper;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\StringContainerInterface;

class CodeGroup extends AbstractBlock implements StringContainerInterface
{
    protected string $literal;

    private array $attrs;

    private int $fenceLength;

    private int $offset;

    public function __construct(string $attrs, int $fenceLength, int $offset)
    {
        parent::__construct();

        $this->setAttrs($attrs);
        $this->offset = $offset;
        $this->fenceLength = $fenceLength;

    }

    public function getAttrs(): array
    {
        return $this->attrs;
    }

    public function setAttrs(string $attrs): void
    {
        $this->attrs = AttributeHelper::parseBlockAttributes($attrs);
    }

    public function getAttr(string $key, $default = null): mixed
    {
        return $this->attrs[$key] ?? $default;
    }

    public function getFenceLength(): int
    {
        return $this->fenceLength;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
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
