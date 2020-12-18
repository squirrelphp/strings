<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithInvalidProperty
{
    /**
     * @StringFilter("Trim")
     */
    #[StringFilter("Trim")]
    public bool $title = false;
}
