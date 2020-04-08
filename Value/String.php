<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Value;

use Clean\Value\Foundation;

/**
 * string value object
 */
class String extends Foundation implements ValueObject
{
    /** @var int $minLength enable min length value. */
    protected $minLength;

    /** @var int $maxLength enable max length value. */
    protected $maxLength;

    /** @var array error description list */
    protected $errorDescriptions = [
        'notString' => 'Not string value.',
        'minLength' => function (string $value) {
            $format = "Can't use %s characters string. You can use more than %s characters.";

            return sprintf($format, mb_strlen($value), $this->minLength);
        },
        'maxLength' => function (string $value) {
            $format = "Can't use %s characters string. Can use up to %s characters";

            return sprintf($format, mb_strlen($value), $this->maxLength);
        }
    ];

    /**
     * set value.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);

        if ($this->validate($value)) {
            $this->value = (string)$value;
        }
    }

    /**
     * parse string this value
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
     * validate value
     *
     * @return bool
     */
    protected function validate($value): bool
    {
        $this->errors = $this->rules->apply($value);

        return !$this->hasErrors();
    }

    /**
     * set default rule.
     */
    protected function setDefaultRule()
    {
        $this->rules
            ->add('notString', [
                'final' => true,
                'rule'  => function ($value) {
                    return !is_scalar($value));
                }
            ])
            ->add('minLength', [
                'rule' => function ($value) {
                    $len = mb_strlen($value);

                    return $this->minLength && $len < $min;
                }
            ])
            ->add('maxLength', [
                'rule' => function ($value) {
                    $len = mb_strlen($value);

                    return $this->maxLength && $len > $max;
                }
            ]);
    }
}
