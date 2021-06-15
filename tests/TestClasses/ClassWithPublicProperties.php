<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Attribute\StringFilter;

class ClassWithPublicProperties
{
    #[StringFilter("Lowercase", "Trim")]
    public $title = '';

    #[StringFilter("Trim")]
    public $text = '';

    public $noAnnotation;
}
