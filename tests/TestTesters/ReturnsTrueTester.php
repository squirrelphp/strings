<?php

namespace Squirrel\Strings\Tests\TestTesters;

use Squirrel\Strings\StringTesterInterface;

class ReturnsTrueTester implements StringTesterInterface
{
    public function test(string $string): bool
    {
        return true;
    }
}
