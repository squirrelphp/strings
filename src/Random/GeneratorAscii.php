<?php

namespace Squirrel\Strings\Random;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\RandomStringGeneratorInterface;

/**
 * Generates a random string with the given $characters and $length
 *
 * If a character occurs multiple times in $characters, the chances
 * of it ending up in the random string will be increased accordingly
 *
 * IMPORTANT: This is not unicode compatible, because str_split splits
 * according to bytes, not characters.
 */
class GeneratorAscii implements RandomStringGeneratorInterface
{
    use InvalidValueExceptionTrait;

    /**
     * @var int Number of possible values
     */
    protected int $valuesNumber = 0;

    /**
     * @var array Possible values for random strings as an array
     */
    protected array $possibleValues = [];

    public function __construct(string $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        // Number of possible values
        $this->valuesNumber = \strlen($characters);

        // Characters have to be defined
        if ($this->valuesNumber === 0) {
            throw $this->generateInvalidValueException('No character set specified for generating random strings');
        }

        // All possible characters as an array
        $this->possibleValues = \str_split($characters);
    }

    public function generate(int $length): string
    {
        // Length has to be positive
        if ($length <= 0) {
            throw $this->generateInvalidValueException('Invalid length (zero or below) specified for generating random string');
        }

        // Initialize the random string
        $generatedString = '';

        // Go through generation of the random string using random_int
        for ($i = 0; $i < $length; $i++) {
            $generatedString .= $this->possibleValues[\random_int(0, $this->valuesNumber - 1)];
        }

        // Return the generated string
        return $generatedString;
    }
}
