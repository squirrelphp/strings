<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Forcefully wrap long words by adding zero width spaces after a certain number of characters - ignore HTML
 */
class WrapLongWordsNoHTMLFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    private int $maxCharacters = 20;

    public function __construct(int $maxCharacters = 20)
    {
        $this->maxCharacters = $maxCharacters;
    }

    public function filter(string $string): string
    {
        $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1\u{200B}", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
