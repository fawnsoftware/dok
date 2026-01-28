<?php

namespace App\Markdown\CodeGroup;

use App\Markdown\Util\BlockHelper;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

class CodeGroupStartParser implements BlockStartParserInterface
{
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        if ($cursor->isIndented() || ! \in_array($cursor->getNextNonSpaceCharacter(), [':'], true)) {
            return BlockStart::none();
        }

        $indent = $cursor->getIndent();
        $fence = $cursor->match(BlockHelper::startBlockRegex('codegroup'));

        if ($fence === null) {
            return BlockStart::none();
        }

        $attrs = $cursor->getRemainder();

        $cursor->advanceToEnd();

        return BlockStart::of(new CodeGroupParser($attrs, substr_count($fence, ':'), $indent))->at($cursor);
    }
}
