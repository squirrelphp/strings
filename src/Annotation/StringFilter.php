<?php

namespace Squirrel\Strings\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class StringFilter
{
    /**
     * @var mixed Filter names which should be executed, either a string or array
     */
    public $names = [];
}
