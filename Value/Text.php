<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Value;

use Clean\Rules\Method;
use Clean\Value\Foundation;

/**
 * string value object
 */
class Text extends Foundation implements ValueObject
{
    /** @var int $minCharacters enable min length value. */
    protected $minCharacters;

    /** @var int $maxCharacters enable max length value. */
    protected $maxCharacters;

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
     * set default rule.
     */
    protected function setDefaultRule()
    {
        $this->rules
            ->add('notString', [
                'final' => true,
                'rule'  => 'isScalar'
            ])
            ->add('minLength', [
                'rule' => 'minCharacters',
                'vars' => ['min' => $this->minCharacters]
            ])
            ->add('maxLength', [
                'rule' => 'maxCharacters',
                'vars' => ['max' => $this->maxCharacters]
            ]);
    }

    /**
     * set error descriptions
     */
    protected function setErrorDescriptions(): void
    {
        $this->errorDescriptions = [
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
    }
}
