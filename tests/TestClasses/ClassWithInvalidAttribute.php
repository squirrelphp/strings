<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Attribute\StringFilter;

class ClassWithInvalidAttribute
{
    #[StringFilter(0)]
    public $title = '';
}
