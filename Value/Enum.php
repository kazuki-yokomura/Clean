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
abstract class Enum extends Foundation
{
    /** @var array $displayMap array(const_name => display) */
    protected $displayMap = [];

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
     * display enum value
     *
     * @return string
     */
    public function display()
    {
        $ref    = new ReflectionObject($this);
        $consts = $ref->getConstants();

        return $this->displayMap[array_search($this->value, $consts)];
    }

    /**
     * check value
     *
     * @param  string $const constant name
     * @return bool
     */
    public function is(string $const): bool
    {
        $constantName = get_class($this) . '::' . $const;
        if (!defined($constantName)) {
            return false;
        }

        return $this->value === constant($constantName);
    }

    /**
     * set default rule
     */
    protected function setRule(): void
    {
        $this->rules
            ->add('correctValue', [
                'final'   => true,
                'method'  => 'hasConstant',
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
