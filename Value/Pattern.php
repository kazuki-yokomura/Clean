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

    protected function setDefaultRule()
    {
        parent::setDefaultRule();
        $this->rules
            ->add('missPattern', [
                'rule' => 'pregMatch',
                'vars' => ['pattern' => $this->pattern]
            ]);
    }

    /**
     * set error descriptions
     */
    protected function setErrorDescriptions(): void
    {
        parent::setErrorDescriptions();
        $this->errorDescriptions['missPattern'] = 'Miss match pattern.';
    }
}
