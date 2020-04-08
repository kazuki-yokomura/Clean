<?php

/**
 * structure value object
 */
class Structure extends Foundation
{
    /**
     * @var array $scheme json scheme
     * array (
     *     {key} => {value object class name}
     * )
     */
    protected $scheme = [];

    public function __construct($value)
    {
        parent::__construct($value);

        $patched = $this->patchScheme($value);

        $value = $this->getParsedValue($value);
        if ($this->validate($value)) {
            $this->value    = $patched;
        }
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)json_encode($value);
    }

    protected function patchScheme(array $input)
    {
        $structure = [];
        foreach ($this->intersectScheme($input) as $key => $scheme) {
            $structure[$key] = new $scheme($input[$key]);
        }

        return $structure;
    }

    /**
     * extruct valid scheme values
     *
     * @param  array  $input input values
     * @return array
     */
    protected function intersectScheme(array $input): array
    {
        return array_intersect_key($this->scheme, $input);
    }

    /**
     * validate structure
     *
     * @param  mixed input value
     * @return bool
     */
    protected function validate($value): bool
    {
        // TODO: 比較
        // TODO: Date
        $this->errors = $this->rules->apply();

        return !$this->hasErrors();
    }

    /**
     * get parsed value
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * get value errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        $valueErrors = [];
        foreach ($this->value as $key => $valueObject) {
            if ($errors = $valueObject->getErrors()) {
                $valueErrors[$key] = $errors;
            }
        }

        return $valueErrors + $this->errors;
    }

    /**
     * has error
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        if ($this->errors) {
            return true;
        }
        foreach ($this->value as $key => $valueObject) {
            if ($valueObject->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /**
     * set default rule
     */
    protected function setDefaultRule(): void
    {
        foreach ($this->scheme as $key => $value) {
            $canEmpty = isset($this->empty[$key]);
            $this->rules->add($key, [
                'rule' => function ($value) use ($key, $canEmpty) {
                    if ($canEmpty) {
                        return true;
                    }

                    return !is_null($value[$key]);
                }
            ]);
        }
    }
}
