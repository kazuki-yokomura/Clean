<?php
namespace Clean\Interfaces\Value;

/**
 * data object interface.
 */
interface ValueObject
{
    public function get();

    public function hasErrors(): bool;

    public function getErrors(): array;
}
