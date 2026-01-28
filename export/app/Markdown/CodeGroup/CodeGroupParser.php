<?php

namespace App\Markdown\CodeGroup;

use App\Markdown\Util\BlockHelper;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Util\ArrayCollection;

class CodeGroupParser extends AbstractBlockContinueParser implements BlockContinueParserInterface
{
    /** @psalm-readonly */
    private CodeGroup $block;

    /** @var ArrayCollection<string> */
    public function __construct(string $attrs, int $fenceLength, int $offset)
    {
        $this->block = new CodeGroup($attrs, $fenceLength, $offset);
    }

    public function getBlock(): CodeGroup
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
        if (BlockHelper::shouldBlockFinish($cursor, 'codegroup', $this->block->getFenceLength())) {
            return BlockContinue::finished();
        }

        return BlockContinue::at($cursor);
    }
}
