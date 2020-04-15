<?php
declare(strict_types=1);
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
            'rule'  => 'canJsonEncode'
        ]);
        parent::setDefaultRule();
    }

    /**
     * set error descriptions
     */
    protected function setErrorDescriptions(): void
    {
        parent::setErrorDescriptions();
        $this->errorDescriptions['__parseError'] = function ($value) {
            return json_last_error_msg();
        };
    }
}
