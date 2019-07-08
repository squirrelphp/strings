<?php

namespace Squirrel\Strings;

interface RandomStringGeneratorSelectInterface
{
    public function getGenerator(string $name): RandomStringGeneratorInterface;
}
