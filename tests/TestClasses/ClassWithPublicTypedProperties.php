<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithPublicTypedProperties
{
    /**
     * @StringFilter({"Lowercase","Trim"})
     */
    public string $title = '';

    /**
     * @StringFilter("Trim")
     */
    public array $texts = [
        '',
        '',
    ];

    public $noAnnotation;
}
