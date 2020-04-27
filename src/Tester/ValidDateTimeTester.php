<?php

namespace Squirrel\Strings\Tester;

use Squirrel\Strings\StringTesterInterface;

class ValidDateTimeTester implements StringTesterInterface
{
    /**
     * @var string The date time format to test strings against
     */
    private string $format;

    public function __construct(string $format = 'Y-m-d')
    {
        $this->format = $format;
    }

    public function test(string $string): bool
    {
        $d = \DateTime::createFromFormat($this->format, $string);
        return $d && $d->format($this->format) === $string;
    }
}
