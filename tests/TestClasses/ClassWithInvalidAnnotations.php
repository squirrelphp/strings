<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithInvalidAnnotations
{
    /**
     * @StringFilter(0)
     */
    public $title = '';
}
