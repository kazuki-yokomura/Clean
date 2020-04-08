<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Value;

use Clean\Value\Foundation;
use ReflectionObject;

/**
 * Enum value object
 */
class Enum extends Foundation implements ValueObject
{
    /**
     * error descriptions
     *
     * @var array
     */
    protected $errorDescriptions = [
        'invalid' => function ($value) {
            $format = 'Invalid argument "%s".';
            if (is_array($value)) {
                $value = json_encode($value);
            }

            return sprintf($format, $value);
        }
    ];

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
     * validate
     *
     * @return bool
     */
    protected function validate($value): bool
    {
        $this->errors = $this->rules->apply($value);

        return !$this->hasErrors();
    }

    /**
     * set default rule
     */
    protected function setDefaultRule(): void
    {
        $this->rule
            ->add('correctValue', [
                'final' => true,
                'rule'  => function ($value) {
                    $ref    = new ReflectionObject($this);
                    $consts = $ref->getConstants();

                    return in_array($value, $consts);
                }
            ]);
    }
}
