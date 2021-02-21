<?php
declare(strict_types=1);
namespace Clean\Value;

/**
 * integer value object
 */
class Integer extends Numeric
{
    /** @inheritdoc */
    protected $precision = 0;

    /** @inheritdoc */
    public function get()
    {
        return (int)$this->value;
    }
}
