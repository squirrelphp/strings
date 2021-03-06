<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Forcefully wrap long words by adding zero width spaces after a certain number of characters - factor in HTML
 */
class WrapLongWordsWithHTMLFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    private int $maxCharacters = 20;

    public function __construct(int $maxCharacters = 20)
    {
        $this->maxCharacters = $maxCharacters;
    }

    public function filter(string $string): string
    {
        // Look for HTML tags
        if (\preg_match_all('/([<][^>]+[>])/si', $string, $tags)) {
            return $this->wrapHTML($string, $tags);
        }

        // No HTML tags found - use the non-HTML wrapper
        return $this->wrapNonHTML($string);
    }

    /**
     * Wrap long words when there is no HTML tags in the string
     */
    private function wrapNonHTML(string $string): string
    {
        $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1\u{200B}", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }

    /**
     * Wrap long words and count HTML elements as only one character each
     */
    private function wrapHTML(string $string, array $tags): string
    {
        // Exchange all tags with character 17
        $string = \preg_replace('/[<][^>]+[>]/si', \chr(17), $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        // Add zero width space to break up long words
        $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1\u{200B}", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        // Put HTML tags back into the string - exchange characters 17 with HTML tags
        foreach ($tags[0] as $key => $val) {
            $string = \preg_replace('/' . \chr(17) . '/si', $tags[0][$key], $string, 1);

            // @codeCoverageIgnoreStart
            if ($string === null) {
                throw $this->generateRegexException();
            }
            // @codeCoverageIgnoreEnd
        }

        return $string;
    }
}
