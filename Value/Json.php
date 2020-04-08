<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Value;

use Clean\Value\Structure;
use Clean\Traits\UseSchemeTrait;

/**
 * json value object
 */
class Json extends Structure implements ValueObject
{
    /** @var int $options json encode/decode options */
    protected $options = 0;

    protected $errorDescriptions = [
        '__parseError' => function ($value) {
            return json_last_error_msg();
        }
    ];

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
        return (string)json_encode($value, $this->options);
    }

    /**
     * json scheme validate
     *
     * @param  mixed $value parsed or stringfy value
     * @return bool
     */
    protected function validate($value): bool
    {
        $this->errors = $this->rules->apply();

        return !$this->hasErrors();
    }

    /**
     * get Parsed value
     *
     * @param  mixed $value parsed or stringfy value
     * @return mixed
     */
    protected function getParsedValue($value)
    {
        if (is_string($value) && $parsed = json_decode($value, false, 512, $this->options)) {
            return $parsed;
        }

        return $value;
    }

    /**
     * set rules
     */
    protected function setDefaultRule()
    {
        $this->rules->add('__parseError', [
            'final' => true,
            'rule'  => function ($value) {
                return json_encode($value, $this->options) !== false;
            }
        ]);
        parent::setDefaultRule();
    }
}
