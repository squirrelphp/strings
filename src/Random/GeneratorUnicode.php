<?php

namespace Squirrel\Strings\Random;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\Common\MultibyteStringSplitTrait;

/**
 * Generates a random string with the given $characters and $length
 *
 * If a character occurs multiple times in $characters, the chances
 * of it ending up in the random string will be increased accordingly
 *
 * Unicode compatible, but more involved than the ascii version
 */
class GeneratorUnicode extends GeneratorAscii
{
    use MultibyteStringSplitTrait;
    use InvalidValueExceptionTrait;

    /**
     * @param string $characters
     */
    public function __construct(string $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        parent::__construct($characters);

        // Number of possible values
        $this->valuesNumber = \mb_strlen($characters, 'UTF-8');

        // All possible characters as an array
        $this->possibleValues = $this->mbStringSplit($characters);
    }
}
