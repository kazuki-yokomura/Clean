<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\Numeric;

/**
 *
 */
class NumericTest extends TestCase
{
    /**
     * @dataProvider inputDataset
     */
    public function testCanInput($value, $hasError)
    {
        $numeric = new Numeric($value);

        $this->assertSame($hasError, $numeric->hasErrors());
    }

    public function inputDataset()
    {
        return [
            'integer' => [
                'value'    => 123,
                'hasError' => false
            ],
            'minus' => [
                'value'    => -123,
                'hasError' => false
            ],
            'float' => [
                'value'    => 1.23,
                'hasError' => false
            ],
            'minusFloat' => [
                'value'    => -1.23,
                'hasError' => false
            ],
            'string' => [
                'value'    => 'string',
                'hasError' => true
            ],
            'array' => [
                'value'    => [1, 2, 3],
                'hasError' => true
            ],
            'mixedArray' => [
                'value'    => [1, 'two', 3],
                'hasError' => true
            ],
            'nestedArray' => [
                'value'    => [1, [2 => 3]],
                'hasError' => true
            ],
            'object' => [
                'value'    => new stdClass(),
                'hasError' => true
            ],
            'json' => [
                'value'    => '{"key": "value", "key2": 3}',
                'hasError' => true
            ],
            'bool' => [
                'value'    => true,
                'hasError' => true
            ],
            'null' => [
                'value'    => null,
                'hasError' => true
            ]
        ];
    }

    /**
     * @dataProvider roundDataset
     */
    public function testRound($value, $option, $expected)
    {
        $numeric = new class($value, $option) extends Numeric {
            public function __construct($value, $option)
            {
                $this->precision = $option['precision'];
                $this->roundType = $option['type'];
                $this->roundHalf = $option['round'];
                parent::__construct($value);
            }
        };

        $this->assertSame($expected, $numeric->get());
    }

    public function roundDataset()
    {
        return [
            'integer_up' => [
                'value'     => 123.5,
                'option'    => [
                    'precision' => 0,
                    'type'      => Numeric::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 124.0
            ],
            'integer_down' => [
                'value'     => 123.4,
                'option'    => [
                    'precision' => 0,
                    'type'      => Numeric::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123.0
            ],
            'float_up' => [
                'value'     => 123.2555,
                'option'    => [
                    'precision' => 3,
                    'type'      => Numeric::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123.256
            ],
            'float_down' => [
                'value'     => 123.0005,
                'option'    => [
                    'precision' => 3,
                    'type'      => Numeric::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_DOWN,
                ],
                'expected'  => 123.0
            ],
            'round_up' => [
                'value'     => 123.4,
                'option'    => [
                    'precision' => 0,
                    'type'      => Numeric::ROUND_TYPE_UP,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 124.0
            ],
            'round_up_float' => [
                'value'     => 123.4444,
                'option'    => [
                    'precision' => 2,
                    'type'      => Numeric::ROUND_TYPE_UP,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123.45
            ],
            'round_down' => [
                'value'     => 123.9,
                'option'    => [
                    'precision' => 0,
                    'type'      => Numeric::ROUND_TYPE_DOWN,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123.0
            ],
            'round_down_float' => [
                'value'     => 123.9999,
                'option'    => [
                    'precision' => 1,
                    'type'      => Numeric::ROUND_TYPE_DOWN,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123.9
            ],
        ];
    }

    /**
     * @dataProvider rangeDataSet
     */
    public function testRange($value, $range, $hasError)
    {
        $numeric = new class($value, $range) extends Numeric {
            public function __construct($value, $range)
            {
                $this->minValue = $range['min'];
                $this->maxValue = $range['max'];
                parent::__construct($value);
            }
        };

        $this->assertSame($hasError, $numeric->hasErrors());
    }

    public function rangeDataSet()
    {
        return [
            'valid' => [
                'value'    => 123,
                'range'    => [
                    'min' => 0,
                    'max' => 200
                ],
                'hasError' => false
            ],
            'under' => [
                'value'    => 123,
                'range'    => [
                    'min' => 200,
                    'max' => 300
                ],
                'hasError' => true
            ],
            'over' => [
                'value'    => 400,
                'range'    => [
                    'min' => 200,
                    'max' => 300
                ],
                'hasError' => true
            ],
            'same' => [
                'value'    => 1234,
                'range'    => [
                    'min' => 1234,
                    'max' => 1234
                ],
                'hasError' => false
            ],
        ];
    }
}
