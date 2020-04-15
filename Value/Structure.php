<?php
declare(strict_types=1);
namespace Clean\Value;

/**
 * structure value object
 */
abstract class Structure
{
    /**
     * @var array $scheme structure scheme
     * array (
     *     {key} => array (
     *         'type'       => {Value Object Class (full Class name)}
     *         'empty'      => {bool or Closure},
     *         'comparison' => array({'under' or 'over' or 'Equal'}, 'key')
     *     )
     * )
     */
    protected $scheme = [];

    public function __construct($value)
    {
        $this->setScheme();
        parent::__construct($value);

        $patched = $this->patch($value);

        $value = $this->getParsedValue($value);
        if ($this->validate($value)) {
            $this->value = $patched;
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

    /**
     * patch input value
     *
     * @param  array  $input input value
     * @return array
     */
    protected function patch(array $input): array
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
    protected function setRule(): void
    {
        // TODO: 比較
        // TODO: Date
        $defaultInfo = [
            'empty'      => false,
            'comparison' => null
        ];

        foreach ($this->scheme as $key => $valueInfo) {
            $valueInfo += $defaultInfo;
            if ($valueInfo['empty']) {
                $this->rules->add($key, [
                    'method' => function ($value) use ($key) {
                        return !empty($value[$key]);
                    }
                ]);
            }
        }
    }

    /**
     * set structure scheme
     */
    abstract public function setScheme(): void;
}
