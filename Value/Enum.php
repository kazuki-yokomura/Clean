<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Foundation;
use ReflectionObject;

/**
 * Enum value object
 *
 * set constract before use
 */
abstract class Enum extends Foundation implements ValueObject
{
    /**
     * validate enum
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);

        if ($this->validate($value)) {
            $this->value = $value;
        }
    }

    /**
     * enum to string
     *
     * @return string
     */
    public function __toString()
    {
        if (is_array($this->value)) {
            return json_encode($this->value);
        }

        return (string)$this->value;
    }

    /**
     * get value
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * check value
     *
     * @param  string $const constant name
     * @return bool
     */
    public function is(string $const): bool
    {
        $expected = constant('self::' . $const);

        return $this->value === $expected;
    }

    /**
     * set default rule
     */
    protected function setRule(): void
    {
        $this->rule
            ->add('correctValue', [
                'final' => true,
                'rule'    => 'hasConstant',
                'vars'    => ['object' => $this],
                'message' => function ($value) {
                    $format = 'Invalid argument "%s".';
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    return sprintf($format, $value);
                }
            ]);
    }
}
