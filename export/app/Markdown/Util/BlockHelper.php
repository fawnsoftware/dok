<?php

namespace App\Markdown\Util;

use League\CommonMark\Parser\Cursor;
use League\CommonMark\Util\RegexHelper;

final class BlockHelper
{
    /**
     * Generates block start regex
     */
    public static function startBlockRegex(string|array $name)
    {
        $pattern = '';

        if (is_array($name)) {
            $pattern = collect($name)
                ->map(fn ($name) => preg_quote($name))
                ->implode('|');
        }

        if (is_string($name)) {
            $pattern = preg_quote($name);
        }

        return '/^[ \t]*(:{3,})('.$pattern.')(?:\.([a-zA-Z0-9._-]+))?(?=$|[ \t\/])/';
    }

    /**
     * Generates the ending regex for container blocks
     *
     * @return string The regex pattern
     */
    public static function endBlockRegex(string $name)
    {
        $name = preg_quote($name);

        return '/^(:{3,})(?:\/('.$name.'))?[ \t]*$/';
    }

    /**
     * Based on the cursor location, checks if the block should finish
     *
     * @return bool If the block should end
     */
    public static function shouldBlockFinish(Cursor $cursor, string $name, int $fenceLength)
    {
        if (! $cursor->isIndented() && $cursor->getNextNonSpaceCharacter() === ':') {
            $match = RegexHelper::matchFirst(
                self::endBlockRegex($name),
                $cursor->getLine(),
                $cursor->getNextNonSpacePosition()
            );

            if ($match !== null && strlen($match[1]) >= $fenceLength) {
                return true;
            }
        }

        return false;
    }
}
