<?php

namespace Squirrel\Strings\Tests\TestClasses;

use Squirrel\Strings\Annotation\StringFilter;

class ClassWithPrivateTypedProperties
{
    /**
     * @StringFilter({"Lowercase","Trim"})
     */
    #[StringFilter("Lowercase", "Trim")]
    private string $title = '';

    /**
     * @StringFilter("Trim")
     */
    #[StringFilter("Trim")]
    private string $text = '';

    private $noAnnotation;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }
}