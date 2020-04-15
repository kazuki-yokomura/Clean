<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Text;

/**
 * regex pattern value object
 */
class Pattern extends Text
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
     * set rule
     */
    protected function setRule(): void
    {
        parent::setRule();
        $this->rules
            ->add('missPattern', [
                'method'  => 'pregMatch',
                'vars'    => ['pattern' => $this->pattern],
                'message' => 'Miss match pattern.'
            ]);
    }
}
