<?php
declare(strict_types=1);
namespace ValueValidator\Value;

use ValueValidator\Value\Foundation;

/**
 * string value object
 */
class Text extends Foundation
{
    /** @var int $minCharacters enable min length value. */
    protected $minCharacters = 0;

    /** @var int $maxCharacters enable max length value. */
    protected $maxCharacters = 0;

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

    /** @inheritdoc */
    public function get(): string
    {
        return $this->value;
    }

    /** @inheritdoc */
    protected function setRule(): void
    {
        $this->rules
            ->add('notString', [
                'final' => true,
                'method' => 'isScalar',
                'message' => 'Not string value.'
            ])
            ->add('minLength', [
                'method' => 'minCharacters',
                'vars' => ['min' => $this->minCharacters],
                'message' => function (string $value) {
                    $format = "Can't use %s characters string. You can use more than %s characters.";

                    return sprintf($format, mb_strlen($value), $this->minCharacters);
                }
            ])
            ->add('maxLength', [
                'method' => 'maxCharacters',
                'vars' => ['max' => $this->maxCharacters],
                'message' => function (string $value) {
                    $format = "Can't use %s characters string. Can use up to %s characters";

                    return sprintf($format, mb_strlen($value), $this->maxCharacters);
                }
            ]);
    }
}
