<?php

namespace Squirrel\Strings;

/**
 * Interface for generating a random string
 */
interface RandomStringGeneratorInterface
{
    /**
     * Generates a random string with the given length $length
     *
     * @param int $length
     * @return string
     */
    public function generate(int $length): string;
}
