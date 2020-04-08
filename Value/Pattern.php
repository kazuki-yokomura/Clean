<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Value;

use Clean\Value\String;

/**
 * regex pattern value object
 */
class Pattern extends String
{
    protected $pattern;

    public function __construct($value)
    {
        parent::__construct($value);

        if ($this->validate($value)) {
            $this->value = (string)$value;
        }
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * return this value
     *
     * @return string
     */
    public function get(): string
    {
        return $this->value;
    }

    /**
     * validate
     *
     * @param  mixed $value input value
     * @return bool
     */
    public function validate($value): bool
    {
        $this->errors = $this->rules->apply($value);

        return !$this->hasErrors();
    }

    protected function setDefaultRule()
    {
        $this->rules
            ->add('notString', [
                'final' => true,
                'rule'  => function ($value) {
                    return is_scalar($value);
                }
            ])
            ->add('missPattern', [
                'rule'  => function ($value) {
                    return preg_match($this->pattern, $value);
                }
            ]);
    }
}
