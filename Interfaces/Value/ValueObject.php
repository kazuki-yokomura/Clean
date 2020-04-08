<?php
namespace Clean\Interfaces\Value;

/**
 * data object interface.
 */
interface ValueObject
{
    public function get();

    public function hasError(): array;

    public function getError(): array;
}
