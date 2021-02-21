<?php
namespace Clean\Interfaces\Value;

/**
 * data object interface.
 */
interface ValueObject
{
    /** get validated value */
    public function get();

    /** get original input value */
    public function getOriginal();

    /**
     * has errors?
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * get validated errors.
     *
     * @return array
     */
    public function getErrors(): array;
}
