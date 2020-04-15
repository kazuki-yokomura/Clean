<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Foundation;

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

    /**
     * return value
     *
     * @return bool
     */
    public function get(): bool
    {
        return $this->value;
    }

    /**
     * nothing validate
     *
     * @return bool
     */
    protected function validate(): bool
    {
        return true;
    }

    /**
     * not check value. All type cast bool.
     */
    protected function setRule(): void
    {
    }
}
