<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Convert newlines to paragraphs and line breaks for HTML displaying
 */
class ReplaceUnixStyleNewlinesWithParagraphsAndBreaksFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // Add paragraphs
        $string = \str_replace("\n\n", '</p><p>', $string);

        // Convert newlines to linebreaks
        $string = \str_replace("\n", '<br/>', $string);

        // Add paragraph enclosings
        return '<p>' . $string . '</p>';
    }
}
