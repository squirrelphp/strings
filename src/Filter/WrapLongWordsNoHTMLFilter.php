<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Forcefully wrap long words by adding spaces after a certain number of characters - ignore HTML
 */
class WrapLongWordsNoHTMLFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var int
     */
    private $maxCharacters = 80;

    /**
     * @param int $maxCharacters
     */
    public function __construct(int $maxCharacters = 80)
    {
        $this->maxCharacters = $maxCharacters;
    }

    public function filter(string $string): string
    {
        $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1 ", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
