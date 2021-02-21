<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Foundation;

/**
 * structure value object
 */
abstract class Structure extends Foundation
{
    protected $value = [];

    /**
     * @var array $scheme structure scheme
     * array (
     *     {key} => array (
     *         'valueObject' => {Value Object Class (full Class name)},
     *         'nullable'    => {allow empty value},
     *         'default'     => {if empty set default},
     *         'moreThan'    => {target key name},
     *         'lessThan'    => {target key name},
     *         'orMore'      => {target key name},
     *         'orLess'      => {target key name},
     *         'same'        => {target key name},
     *         'equals'      => {target key name}
     *     )
     * )
     */
    protected $scheme = [];

    public function __construct($value)
    {
        parent::__construct($value);

        $patched = $this->patch($value);
        if ($this->validate($patched)) {
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
        foreach ($this->scheme as $key => $valueInfo) {
            if ($this->needSetDefault($input, $key)) {
                $input[$key] = $valueInfo['default'];
            }
            if (!isset($input[$key])) {
                $structure[$key] = null;
            }
            if ($this->needSkipPatch($input, $key)) {
                continue;
            }
            $structure[$key] = new $valueInfo['valueObject']($input[$key]);
        }

        return $structure;
    }

    /**
     * need set default
     *
     * @param  array  $input input values
     * @param  string $key   key
     * @return bool
     */
    protected function needSkipPatch(array $input, string $key)
    {
        $valueInfo = $this->scheme[$key];

        return $valueInfo['nullable'] && is_null($input[$key]);
    }

    /**
     * need set default
     *
     * @param  array  $input input values
     * @param  string $key   key
     * @return bool
     */
    protected function needSetDefault(array $input, string $key): bool
    {
        $valueInfo = $this->scheme[$key];

        return !$valueInfo['nullable'] && !isset($input[$key]) && isset($valueInfo['default']);
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

    /** @inheritdoc */
    public function get()
    {
        return $this->value;
    }

    /** @inheritdoc */
    public function getErrors(): array
    {
        $valueErrors = [];
        foreach ($this->value as $key => $valueObject) {
            if (is_null($valueObject)) {
                continue;
            }
            if ($errors = $valueObject->getErrors()) {
                $valueErrors[$key] = $errors;
            }
        }

        return $valueErrors + $this->errors;
    }

    /** @inheritdoc */
    public function hasErrors(): bool
    {
        if ($this->errors) {
            return true;
        }
        foreach ($this->value as $key => $valueObject) {
            if (is_null($valueObject)) {
                continue;
            }
            if ($valueObject->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /** @inheritdoc */
    protected function setRule(): void
    {
        $defaultInfo = [
            'nullable'  => false,
            'moreThan'  => null,
            'lessThan'  => null,
            'orMore'    => null,
            'orLess'    => null,
            'same'      => null,
            'equals'    => null,
        ];

        // TODO: message
        foreach ($this->scheme as $key => $valueInfo) {
            $valueInfo += $defaultInfo;
            if ($valueInfo['nullable']) {
                $this->rules->add($key, [
                    'allow'    => true,
                    'method'   => 'isNull',
                    'vars'     => ['key' => $key],
                    'provider' => 'Clean\Rule\AllowMethod'
                ]);
            } else {
                $this->rules->add($key . '.isNotNull', [
                    'method'   => 'isNotNull',
                    'vars'     => ['key' => $key],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['moreThan']) {
                $this->rules->add($key . '.moreThan', [
                    'method' => 'moreThan',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['lessThan']) {
                $this->rules->add($key . '.lessThan', [
                    'method' => 'lessThan',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['moreOr']) {
                $this->rules->add($key . '.moreOr', [
                    'method' => 'moreOr',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['lessOr']) {
                $this->rules->add($key . '.lessOr', [
                    'method' => 'lessOr',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['same']) {
                $this->rules->add($key . '.same', [
                    'method' => 'same',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
            if ($target = $valueInfo['equals']) {
                $this->rules->add($key . '.equals', [
                    'method' => 'equals',
                    'vars'   => [
                        'key'      => $key,
                        'target'   => $target,
                    ],
                    'provider' => 'Clean\Rule\StructureMethod'
                ]);
            }
        }
    }
}
