<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Replace all non alphanumeric characters with a replacement character
 */
class ReplaceNonAlphanumericFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var string The character used to replace any non-alphanumeric characters
     */
    private string $replacementCharacter;

    public function __construct(string $replacementCharacter = '-')
    {
        $this->replacementCharacter = $replacementCharacter;
    }

    public function filter(string $string): string
    {
        $string = \preg_replace('#[^A-Za-z0-9]+#s', $this->replacementCharacter, $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
