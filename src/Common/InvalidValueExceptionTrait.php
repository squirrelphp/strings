<?php

namespace Squirrel\Strings\Common;

use Squirrel\Strings\Annotation\StringFilterExtension;
use Squirrel\Strings\Annotation\StringFilterProcessor;
use Squirrel\Strings\CondenseNumberInterface;
use Squirrel\Strings\Debug;
use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\RandomStringGeneratorInterface;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterSelectInterface;

trait InvalidValueExceptionTrait
{
    private function generateInvalidValueException(string $message): \Throwable
    {
        return Debug::createException(InvalidValueException::class, [
            StringFilterInterface::class,
            StringFilterSelectInterface::class,
            RandomStringGeneratorInterface::class,
            RandomStringGeneratorSelectInterface::class,
            CondenseNumberInterface::class,
            StringFilterProcessor::class,
            StringFilterExtension::class,
        ], $message);
    }
}
