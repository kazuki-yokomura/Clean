<?php
declare(strict_types=1, encoding='UTF-8');

namespace Clean\Value;

/**
 * data object foundation
 */
abstract class Foundation
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
        $this->setDefaultRule();
        $this->setErrorDescriptions();

        $this->original = $value;
    }

    /**
     * if invoke object, create self data object.
     *
     * @return self
     */
    public function __invoke($value)
    {
        return new self($value);
    }

    /**
     * if invoke object, create self data object.
     *
     * @return self
     */
    public function getErrors(string $name, string $description): array
    {
        return $this->errors;
    }

    /**
     * return error messages.
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->errors as $name) {
            $message = $this->errorDescriptions[$name];
            if (is_a($message, 'Closure')) {
                $messages[] = $message($this->original);
            } else {
                $messages[] = $message;
            }
        }

        return $message;
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
     * has error this data
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return (bool)$this->errors;
    }

    /**
     * get original value
     *
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * set default rules
     */
    abstract protected function setDefaultRule(): void;

    /**
     * set error discriptions.
     */
    abstract protected function setErrorDescriptions(): void;
}
