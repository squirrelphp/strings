<?php

namespace Squirrel\Strings\Tests\ExceptionTestClasses;

use Squirrel\Strings\Debug;
use Squirrel\Strings\Exception\StringException;

class SomeClass
{
    public function someFunction()
    {
        return \array_map(function () {
            throw Debug::createException(StringException::class, [SomeClass::class], 'Something went wrong!', null);
        }, ['dada','mumu']);
    }
}
