<?php
declare(strict_types=1);
namespace ValueValidator\Value;

use ValueValidator\Value\Foundation;

/**
 * bool value object
 */
class Boolean extends Foundation
{
    /**
     * cast to bool
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);

        $this->value = (bool)$value;
    }

    /**
     * to string value
     *
     * @return string
     */
    public function __toString()
    {
        return (string)(int)$this->value;
    }

    /** @inheritdoc */
    public function get(): bool
    {
        return $this->value;
    }

    /**
     * nothing validate
     *
     * @inheritdoc
     */
    protected function validate($value): bool
    {
        return true;
    }

    /**
     * not check value. All type cast bool.
     *
     * @inheritdoc
     */
    protected function setRule(): void
    {
    }
}
