<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Attribute\StringFilter;

class ClassWithPublicPropertyPromotion
{
    public function __construct(
        #[StringFilter("Lowercase", "Trim")]
        public string $title,

        #[StringFilter("Trim")]
        public string $text,

        #[StringFilter("Lowercase", "Trim")]
        public string $other,
    ) {
    }
}
