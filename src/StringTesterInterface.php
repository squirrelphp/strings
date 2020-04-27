<?php

namespace Squirrel\Strings;

interface StringTesterInterface
{
    /**
     * Test the string and return either true or false depending on if the string passed the test
     *
     * @return bool True if the string passed the test, false if it failed the test
     */
    public function test(string $string): bool;
}
