<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

class TrimFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var string The characters which should be trimmed from the beginning and end of the string
     */
    private string $trimCharacters;

    /**
     * @var string Default is to trim ascii characters
     */
    private string $trimFunction = 'trimAscii';

    public function __construct(string $trimCharacters = " \t\n\r\0\x0B")
    {
        // If trimCharacters contains unicode characters we change to the trimUnicode function
        if (\mb_strlen($trimCharacters, 'UTF-8') !== \strlen($trimCharacters)) {
            $this->trimFunction = 'trimUnicode';
        }

        $this->trimCharacters = $trimCharacters;
    }

    public function filter(string $string): string
    {
        /**
         * @var callable $callable
         */
        $callable = [$this, $this->trimFunction];

        return $callable($string, $this->trimCharacters);
    }

    private function trimAscii(string $string, string $trimCharacters): string
    {
        return \trim($string, $trimCharacters);
    }

    private function trimUnicode(string $string, string $trimCharacters): string
    {
        $string = \preg_replace('/^[' . \preg_quote($trimCharacters, '/') . ']+/u', '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        $string = \preg_replace('/[' . \preg_quote($trimCharacters, '/') . ']+$/u', '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
