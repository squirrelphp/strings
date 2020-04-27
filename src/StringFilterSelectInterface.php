<?php

namespace Squirrel\Strings;

interface StringFilterSelectInterface
{
    /**
     * Get a specific filter
     */
    public function getFilter(string $name): StringFilterInterface;
}
