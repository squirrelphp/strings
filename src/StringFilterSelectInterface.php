<?php

namespace Squirrel\Strings;

interface StringFilterSelectInterface
{
    /**
     * Get a specific filter
     *
     * @param string $name
     * @return StringFilterInterface
     */
    public function getFilter(string $name): StringFilterInterface;
}
