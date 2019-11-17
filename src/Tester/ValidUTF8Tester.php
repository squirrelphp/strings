<?php

namespace Squirrel\Strings\Tester;

use Squirrel\Strings\StringTesterInterface;

class ValidUTF8Tester implements StringTesterInterface
{
    public function test(string $string): bool
    {
        return \mb_check_encoding($string, 'UTF-8');
    }
}
