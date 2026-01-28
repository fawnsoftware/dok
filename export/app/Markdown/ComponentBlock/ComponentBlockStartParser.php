<?php

namespace App\Markdown\ComponentBlock;

use App\Markdown\Util\BlockHelper;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

class ComponentBlockStartParser implements BlockStartParserInterface
{
    private array $components;

    public function __construct()
    {
        $this->components = config('dok.markdown.components', []);
    }

    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {

        if (empty($this->components)) {
            return BlockStart::none();
        }

        if ($cursor->isIndented() || $cursor->getNextNonSpaceCharacter() !== ':') {
            return BlockStart::none();
        }

        $indent = $cursor->getIndent();

        $pattern = BlockHelper::startBlockRegex(
            collect($this->components)->keys()->merge(['slot'])->all()
        );

        $match = $cursor->match($pattern);

        if ($match === null) {
            return BlockStart::none();
        }

        preg_match($pattern, $match, $parts);

        [$fence, $componentName, $modifier] = [$parts[1], $parts[2], $parts[3] ?? ''];

        $attrs = trim($cursor->getRemainder());

        $cursor->advanceToEnd();

        return BlockStart::of(
            new ComponentBlockParser(
                $componentName,
                $modifier,
                $attrs,
                substr_count($fence, ':'),
                $indent,
                $this->getType($componentName),
                $this->getComponentView($componentName),
                $this->getSlots($componentName),
            )
        )->at($cursor);
    }

    private function buildRegexPattern()
    {
        $pattern = collect($this->components)
            ->keys()
            ->merge(['slot'])
            ->map(fn ($name) => preg_quote($name))
            ->implode('|');

        return '/^[ \t]*(:{3,})('.$pattern.')(?:\.([a-zA-Z0-9._-]+))?(?=$|[ \t\/])/';
    }

    private function getComponentView(string $componentName): string
    {
        return $this->components[$componentName]['template'] ?? '';
    }

    private function getType(string $componentName): string
    {
        return $componentName === 'slot' ? 'slot' : 'component';
    }

    private function getSlots(string $componentName): array
    {
        return $this->components[$componentName]['slots'] ?? [];
    }
}
