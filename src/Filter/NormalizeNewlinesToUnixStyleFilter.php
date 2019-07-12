<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Normalize all possible unicode newlines to unix style (\n)
 */
class NormalizeNewlinesToUnixStyleFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // \r\n to \n
        $string = \str_replace("\u{000D}\u{000A}", "\n", $string);

        // \r to \n
        $string = \str_replace("\u{000D}", "\n", $string);

        // Form feeds to \n
        $string = \str_replace("\u{000C}", "\n", $string);

        // Vertical tabs to \n
        $string = \str_replace("\u{000B}", "\n", $string);

        // Next line to \n
        $string = \str_replace("\u{0085}", "\n", $string);

        // Line separator to \n
        $string = \str_replace("\u{2028}", "\n", $string);

        // Paragraph separator to \n
        $string = \str_replace("\u{2029}", "\n", $string);

        return $string;
    }
}
