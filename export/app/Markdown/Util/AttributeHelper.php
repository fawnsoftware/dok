<?php

namespace App\Markdown\Util;

use League\CommonMark\Util\RegexHelper;

final class AttributeHelper
{
    /**
     * Parse an attribute string
     *
     * @return array The key value pair array
     */
    public static function parseBlockAttributes(string $attributesList): array
    {

        preg_match_all('/([a-zA-Z][a-zA-Z0-9-]*)(?:=(?|"([^"]*)"|\'([^\']*)\'|([^\s"\']+)))?/', $attributesList, $matches, PREG_SET_ORDER);

        $result = [];

        foreach ($matches as $match) {
            // If it has no 2 index key, it's a single attribute
            if (! isset($match[2])) {
                // Do the check to ensure the attribute doesnt contain numbers,
                // otherwise the regex is gonna get even more out of hand...
                if (preg_match('/\d/', $match[1])) {
                    continue;
                }

                $result[$match[1]] = 'true';

                continue;
            }

            $key = $match[1];
            $value = $match[2];

            if (
                RegexHelper::isLinkPotentiallyUnsafe(trim($value))) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
