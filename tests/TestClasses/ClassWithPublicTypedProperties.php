<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithPublicTypedProperties
{
    /**
     * @StringFilter({"Lowercase","Trim"})
     */
    #[StringFilter("Lowercase", "Trim")]
    public string $title = '';

    /**
     * @StringFilter("Trim")
     */
    #[StringFilter("Trim")]
    public array $texts = [
        '',
        '',
    ];

    public $noAnnotation;
}
