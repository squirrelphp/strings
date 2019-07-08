<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class TrimFilter implements StringFilterInterface
{
    /**
     * @var string The characters which should be trimmed from the beginning and end of the string
     */
    private $trimCharacters;

    public function __construct(string $trimCharacters = " \t\n\r\0\x0B")
    {
        $this->trimCharacters = $trimCharacters;
    }

    public function filter(string $string): string
    {
        return \trim($string, $this->trimCharacters);
    }
}
