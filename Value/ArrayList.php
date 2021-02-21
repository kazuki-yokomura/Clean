<?php
declare(strict_types=1);
namespace ValueValidator\Value;

use ValueValidator\Value\Foundation;

/**
 * ArrayList
 */
abstract class ArrayList extends Foundation implements \Iterator, \ArrayAccess
{
    /** @var array $position position  */
    protected $position = false;

    /** @var array $allowKeys allow key value  */
    protected $allowKeys = [];

    /** @var string $valueClass list value */
    protected $valueClass = '';

    /**
     * constractor
     *
     * @param mixed  $value      input value
     * @param string $valueClass arraylist element class
     */
    public function __construct($value)
    {
        $this->setAllowKeys();
        parent::__construct($value);

        if (is_array($value)) {
            $value = $this->patch($value);
        }
        if ($this->validate($value)) {
            $this->value = $value;
        }
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)json_encode($this->value);
    }

    /**
     * patch input value
     *
     * @param  array $input input value
     * @return array
     */
    protected function patch(array $input): array
    {
        $arrayList = [];
        $class = $this->valueClass;
        foreach ($input as $key => $value) {
            $arrayList[$key] = new $class($value);
        }

        return $arrayList;
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
        foreach ($this->value ?? [] as $key => $valueObject) {
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
        foreach ($this->value ?? [] as $key => $valueObject) {
            if ($valueObject->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /** @inheritdoc */
    protected function setRule(): void
    {
        $this->rules
            ->add('invalid', [
                'final' => true,
                'method' => 'isArray',
                'message' => 'Not array.'
            ]);
        if ($this->allowKeys) {
            $this->rules
                ->add('isAllowKeys', [
                    'final' => true,
                    'method' => 'isAllowKeys',
                    'vars' => ['keys' => $this->allowKeys],
                    'message' => 'invalid keys.',
                    'provider' => 'ValueValidator\Rule\StructureMethod'
                ]);
        }
    }

    /************************************************
     *
     * Iterator
     *
     ************************************************/

    /**
     * rewind this value
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = array_key_first($this->value);
    }

    /**
     * current value
     *
     * @return mixed
     */
    public function current()
    {
        return $this->value[$this->position];
    }

    /**
     * current position
     *
     * @return scalar
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * next position
     *
     * @return void
     */
    public function next()
    {
        $tmp = false;
        foreach ($this->value as $key => $v) {
            if ($key == $this->position) {
                $tmp = true;
            } elseif ($tmp !== false) {
                $this->position = $key;
                return;
            }
        }

        $this->position = false;
    }

    /**
     * valid key
     *
     * @return bool
     */
    public function valid(): bool
    {
        if ($this->position === false) {
            return false;
        }

        return isset($this->value[$this->position]);
    }

    /************************************************
     *
     * ArrayAccess
     *
     ************************************************/

    /**
     * offset set
     *
     * @param scalar $offset array key
     * @param mixed  $value  set   value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->value[] = $value;
        } else {
            $this->value[$offset] = $value;
        }
    }

    /**
     * offset exists
     *
     * @param  scalar $offset array key
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->value[$offset]);
    }

    /**
     * offset unset
     *
     * @param  scalar $offset array key
     */
    public function offsetUnset($offset)
    {
        unset($this->value[$offset]);
    }

    /**
     * offset get
     *
     * @param  scalar $offset array key
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->value[$offset]) ? $this->value[$offset] : null;
    }

    /**
     * set allow keys
     */
    protected function setAllowKeys() :void
    {
        $this->allowKeys = [];
    }
}
