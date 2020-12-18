<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithPublicProperties
{
    /**
     * @StringFilter({"Lowercase","Trim"})
     */
    #[StringFilter("Lowercase", "Trim")]
    public $title = '';

    /**
     * @StringFilter("Trim")
     */
    #[StringFilter("Trim")]
    public $text = '';

    public $noAnnotation;
}
