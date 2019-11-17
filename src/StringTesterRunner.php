<?php

namespace Squirrel\Strings;

/**
 * Wrapper to combine multiple string tests in order and abort when any one of them fails
 */
class StringTesterRunner implements StringTesterInterface
{
    /**
     * @var StringTesterInterface[]
     */
    private $stringTesters = [];

    public function __construct(StringTesterInterface ...$stringTesters)
    {
        $this->stringTesters = $stringTesters;
    }

    public function test(string $string): bool
    {
        // Go through all our tests and when one fails the whole group of tests is seen as "failed"
        foreach ($this->stringTesters as $stringTester) {
            if ($stringTester->test($string) === false) {
                return false;
            }
        }

        return true;
    }
}
