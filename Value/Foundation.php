<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Interfaces\Value\ValueObject;
use Clean\Rule\Rules;

/**
 * data object foundation
 */
abstract class Foundation implements ValueObject
{
    /** @var array $errors */
    protected $errors = [];

    /** @var array $errorDescriptions */
    protected $errorDescriptions = [];

    /** @var mixed $original original input value. */
    protected $original;

    /** @var mixed $value */
    protected $value;

    /** @var Rules value rule */
    protected $rules;

    /**
     * input value validate
     */
    public function __construct($value)
    {
        $this->rules = new Rules();
        $this->setRule();

        $this->original = $value;
    }

    /**
     * if invoke object, create self data object.
     *
     * @return self
     */
    public function __invoke(...$constructValues)
    {
        $self = get_class($this);

        return new $self(...$constructValues);
    }

    /** @inheritdoc */
    public function getErrors(): array
    {
        return $this->errors;
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

    /** @inheritdoc */
    public function hasErrors(): bool
    {
        return (bool)$this->errors;
    }

    /** @inheritdoc */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * set default rules
     */
    abstract protected function setRule(): void;

    // TODO: やっぱりエラ〜メッセージは継承したときように必要だわ。
    // もう毎回 value が渡ってくるようにして、メッセージプロパティに入れるようにしよう
    // TODO: 仕様クラスを作って、ここでセットするんじゃなくて、ここでは仕様を継承するようにしよう。
}
