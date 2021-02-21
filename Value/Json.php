<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Structure;

/**
 * json value object
 */
abstract class Json extends Structure
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
    protected function setRule(): void
    {
        $this->rules->add('__parseError', [
            'final'   => true,
            'method'  => 'canJsonEncode',
            'message' => function ($value) {
                return json_last_error_msg();
            }
        ]);
        parent::setRule();
    }
}
