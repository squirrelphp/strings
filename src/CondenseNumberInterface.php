<?php

namespace Squirrel\Strings;

/**
 * Interface for condensing a number into a string to shorten it
 */
interface CondenseNumberInterface
{
    /**
     * Convert a number to a string
     *
     * @param int $number
     * @return string
     */
    public function toString(int $number): string;

    /**
     * Convert a string to a number
     *
     * @param string $string
     * @return int
     */
    public function toNumber(string $string): int;
}
