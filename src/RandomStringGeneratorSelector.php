<?php

namespace Squirrel\Strings;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;

/**
 * Handles all random string generators
 */
class RandomStringGeneratorSelector implements RandomStringGeneratorSelectInterface
{
    use InvalidValueExceptionTrait;

    /**
     * List of generators
     *
     * @var array<string, RandomStringGeneratorInterface>
     */
    private array $generators = array();

    /**
     * @param array<string, RandomStringGeneratorInterface> $generators
     */
    public function __construct(array $generators)
    {
        $this->generators = $generators;
    }

    /**
     * Get a specific random generator
     */
    public function getGenerator(string $name): RandomStringGeneratorInterface
    {
        // Generator was not found
        if (!isset($this->generators[$name])) {
            throw $this->generateInvalidValueException('Random generator with the name "' . $name . '" not found');
        }

        return $this->generators[$name];
    }
}
