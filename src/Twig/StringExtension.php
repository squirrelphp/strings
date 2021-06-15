<?php

namespace Squirrel\Strings\Twig;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\StringFilterSelectInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension adding string_filter and string_random functionality to twig templates
 */
class StringExtension extends AbstractExtension
{
    use InvalidValueExceptionTrait;

    public function __construct(
        private StringFilterSelectInterface $stringFilterSelector,
        private RandomStringGeneratorSelectInterface $randomStringGeneratorSelector,
    ) {
    }

    /**
     * Add string_filter as a filter
     *
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('string_filter', \Closure::fromCallable([$this, 'stringFilter'])),
        ];
    }

    /**
     * Add string_random function and also string_filter as a function
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('string_filter', \Closure::fromCallable([$this, 'stringFilter'])),
            new TwigFunction('string_random', function (string $generator, int $length) {
                return $this->randomStringGeneratorSelector->getGenerator($generator)->generate($length);
            }),
        ];
    }

    private function stringFilter(string $string, mixed $filters): string
    {
        if (\is_array($filters)) {
            foreach ($filters as $filter) {
                $string = $this->stringFilterSelector->getFilter($filter)->filter($string);
            }
        } elseif (\is_string($filters)) {
            $string = $this->stringFilterSelector->getFilter($filters)->filter($string);
        } else {
            throw $this->generateInvalidValueException('String filters have to be given as a string or an array');
        }

        return $string;
    }
}
