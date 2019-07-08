<?php

namespace Squirrel\Strings\Condense;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\Common\MultibyteStringSplitTrait;
use Squirrel\Strings\CondenseNumberInterface;

/**
 * Converts a number to a condensed string - this way we can reduce the number
 * of characters used and still keep it readable. Ideal for links, codes etc.
 * which are actually integers but are sent as strings to save space.
 *
 * Unicode compatible, but more involved than the ascii version
 */
class ConverterUnicode implements CondenseNumberInterface
{
    use MultibyteStringSplitTrait;
    use InvalidValueExceptionTrait;

    /**
     * How many characters are in our conversion arsenal
     *
     * @var int
     */
    private $characterCount = 0;

    /**
     * Converter: number as key, string as value
     *
     * @var array<int, string>
     */
    private $numberToString = [];

    /**
     * Converter: string as key, number as value
     *
     * @var array<string, int>
     */
    private $stringToNumber = [];

    /**
     * @param string $characters
     */
    public function __construct(string $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        // Characters have to be defined
        if (\strlen($characters) === 0) {
            throw $this->generateInvalidValueException('No character set specified for converting between numbers and strings');
        }

        // Converter from a number to the string value
        $this->numberToString = $this->mbStringSplit($characters);

        // Converter from a string value to a number
        $this->stringToNumber = \array_flip($this->numberToString);

        // Sanity check that no character appears twice
        if (\count($this->numberToString) <> \count($this->stringToNumber)) {
            throw $this->generateInvalidValueException('Character seems to be appearing twice in character set, which is not allowed / ambiguous');
        }

        // How many different characters occur
        $this->characterCount = \count($this->numberToString);
    }

    /**
     * @inheritDoc
     */
    public function toString(int $number): string
    {
        /**
         * @var int[] $intValues Contains modulus results from the conversion
         */
        $intValues = [];

        /*
         * Iteration through $number:
         * - get modulus
         * - save modulus
         * - subtract modulus
         * - divide by character count
         * - repeat
         */
        for (; $number >= $this->characterCount; $number -= $number % $this->characterCount, $number /= $this->characterCount) {
            $intValues[] = $number % $this->characterCount;
        }

        // Get last modulus - we stopped in the loop above when $number was below 36
        $intValues[] = $number;

        // String to return as valid base36 "number"
        $numberString = '';

        // Go through the collected modulus values and convert them to characters
        /** @var int[] $intValues */
        foreach ($intValues as $val) {
            $numberString .= $this->numberToString[$val];
        }

        // Return base36 "number"
        return $numberString;
    }

    /**
     * @inheritDoc
     */
    public function toNumber(string $string): int
    {
        // Get length of the string
        $length = \mb_strlen($string);

        // More than 16 characters? This would be a HUGE number we cannot really process
        if ($length > 16) {
            throw $this->generateInvalidValueException('String value is too long, resulting number would be impossible to handle');
        }

        // Start number in which we add all the values
        $number = 0;

        // Split the string into individual characters and reverse it
        $stringCharacters = \array_reverse($this->mbStringSplit($string));

        /*
         * Iterate through string
         * - multiply current number with 36
         * - get next rightmost character of the string and add it to number (that's the modulus we saved)
         * - and start again until there's no more string
         */
        foreach ($stringCharacters as $stringValue) {
            $number = $number * $this->characterCount;
            $number = $number + $this->stringToNumber[$stringValue];
        }

        // Return generated number
        return $number;
    }
}
