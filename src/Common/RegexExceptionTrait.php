<?php

namespace Squirrel\Strings\Common;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Attribute\StringFilterExtension;
use Squirrel\Strings\Attribute\StringFilterProcessor;
use Squirrel\Strings\CondenseNumberInterface;
use Squirrel\Strings\Exception\RegexException;
use Squirrel\Strings\RandomStringGeneratorInterface;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterSelectInterface;

trait RegexExceptionTrait
{
    private function generateRegexException(): \Throwable
    {
        /**
         * @var array $constants
         */
        $constants = \get_defined_constants(true)['pcre'];

        // Find the regex error code
        foreach ($constants as $name => $value) {
            if ($value === \preg_last_error()) {
                $error = $name;
            }
        }

        return Debug::createException(RegexException::class, [
            StringFilterInterface::class,
            StringFilterSelectInterface::class,
            RandomStringGeneratorInterface::class,
            RandomStringGeneratorSelectInterface::class,
            CondenseNumberInterface::class,
            StringFilterProcessor::class,
            StringFilterExtension::class,
        ], 'Regex error in ' . __CLASS__ . ': ' . ( $error ?? 'Unrecognized error code' ));
    }
}
