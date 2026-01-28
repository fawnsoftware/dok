<?php

namespace App\Markdown\ComponentBlock;

use App\Markdown\Util\BlockHelper;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;

class ComponentBlockParser extends AbstractBlockContinueParser implements BlockContinueParserInterface
{
    private ComponentBlock $block;

    protected string $componentName;

    protected string $type;

    public function __construct(
        string $componentName,
        string $modifier,
        string $attrs,
        int $length,
        int $offset,
        string $type,
        string $componentView,
        array $slots,
    ) {
        $this->type = $type;
        $this->componentName = $componentName;

        $this->block = new ComponentBlock(
            $componentName,
            $modifier,
            $attrs,
            $length,
            $offset,
            $type,
            $componentView,
            $slots,
        );
    }

    public function getBlock(): ComponentBlock
    {
        return $this->block;
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        return true;
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return false;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        $name = $this->type == 'component' ? $this->componentName : 'slot';

        if (BlockHelper::shouldBlockFinish($cursor, $name, $this->block->getLength())) {
            return BlockContinue::finished();
        }

        return BlockContinue::at($cursor);
    }
}
