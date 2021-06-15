<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterSelector;

class StringFilterSelectorTest extends \PHPUnit\Framework\TestCase
{
    public function testRegular(): void
    {
        $filters = [
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ];

        $manager = new StringFilterSelector($filters);

        $this->assertSame($filters['Lowercase'], $manager->getFilter('Lowercase'));
        $this->assertSame($filters['Trim'], $manager->getFilter('Trim'));
    }

    public function testNotFound(): void
    {
        $this->expectException(InvalidValueException::class);

        $manager = new StringFilterSelector([]);
        $manager->getFilter('dada');
    }
}
