<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Attribute\StringFilter;

class ClassWithInvalidProperty
{
    #[StringFilter("Trim")]
    public bool $title = false;
}
