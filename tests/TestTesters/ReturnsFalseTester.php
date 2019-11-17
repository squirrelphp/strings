<?php

namespace Squirrel\Strings\Tests\TestTesters;

use Squirrel\Strings\StringTesterInterface;

class ReturnsFalseTester implements StringTesterInterface
{
    public function test(string $string): bool
    {
        return false;
    }
}
