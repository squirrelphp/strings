<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Attribute\StringFilter;

class ClassWithPrivateProperties
{
    #[StringFilter("Lowercase", "Trim")]
    private $title;

    #[StringFilter(["Trim"])]
    private $text;

    private $noAnnotation;

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}
