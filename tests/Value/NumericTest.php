<?php

use Clean\Value\Integer;
use Clean\Value\Numeric;
use PHPUnit\Framework\TestCase;

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

    /**
     * @dataProvider moreThanDataSet
     */
    public function testMoreThan($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->moreThan($target));
    }

    public function moreThanDataSet()
    {
        return [
            'valid' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => true
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456,
                'expected' => false
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => false
            ],
            'float_valid' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => false
            ],
            'rounded_value' => [
                'value'    => 1345.001,
                'target'   => 1345,
                'expected' => false
            ],
            'tolerance_string_over' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => true
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => false
            ],
            'obj_valid' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_same' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_under' => [
                'value'    => 455,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_float_valid' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'obj_float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'obj_float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => false
            ],
            'obj_rounded_value' => [
                'value'    => 1345.001,
                'target'   => new Integer(1345),
                'expected' => false
            ]
        ];
    }

    /**
     * @dataProvider moreLessDataSet
     */
    public function testLessThan($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->lessThan($target));
    }

    public function moreLessDataSet()
    {
        return [
            'over' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => false
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456,
                'expected' => false
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => true
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => true
            ],
            'rounded_value' => [
                'value'    => 1345.999,
                'target'   => 1346,
                'expected' => false
            ],
            'tolerance_string_value' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => false
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => true
            ],
            'obj_over' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_same' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_under' => [
                'value'    => 455,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_float_over' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'obj_float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'obj_float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => true
            ],
            'obj_rounded_value' => [
                'value'    => 1345.999,
                'target'   => new Integer(1346),
                'expected' => false
            ],
        ];
    }

    /**
     * @dataProvider moreOrDataSet
     */
    public function testMoreOr($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->moreOr($target));
    }

    public function moreOrDataSet()
    {
        return [
            'over' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => true
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456,
                'expected' => true
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => false
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => false
            ],
            'rounded_value' => [
                'value'    => 1345.001,
                'target'   => 1345,
                'expected' => true
            ],
            'tolerance_string_over' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => true
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => false
            ],
            'obj_over' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_same' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_under' => [
                'value'    => 455,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_float_over' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'obj_float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'obj_float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => false
            ],
            'obj_rounded_value' => [
                'value'    => 1345.001,
                'target'   => new Integer(1345),
                'expected' => true
            ],
        ];
    }

    /**
     * @dataProvider moreLessOr
     */
    public function testLessOr($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->lessOr($target));
    }

    public function moreLessOr()
    {
        return [
            'over' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => false
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456,
                'expected' => true
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => true
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => true
            ],
            'rounded_value' => [
                'value'    => 1345.999,
                'target'   => 1346,
                'expected' => true
            ],
            'tolerance_string_value' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => false
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => true
            ],
            'obj_over' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_same' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_under' => [
                'value'    => 455,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'obj_float_over' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'obj_float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'obj_float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => true
            ],
            'obj_rounded_value' => [
                'value'    => 1345.999,
                'target'   => new Integer(1346),
                'expected' => true
            ],
        ];
    }

    /**
     * @dataProvider sameDataSet
     */
    public function testSame($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->same($target));
    }

    public function sameDataSet()
    {
        return [
            'over' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => false
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456.0,
                'expected' => true
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => false
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => false
            ],
            'rounded_value' => [
                'value'    => 1345.001,
                'target'   => 1345.0,
                'expected' => true
            ],
            'tolerance_string_over' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => false
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => false
            ],
            'equals' => [
                'value'    => 710,
                'target'   => '710',
                'expected' => false
            ],
            'obj_over' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_same' => [
                'value'    => 456,
                'target'   => new Numeric(456),
                'expected' => true
            ],
            'obj_equals' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'obj_under' => [
                'value'    => 455,
                'target'   => new Numeric(456),
                'expected' => false
            ],
            'obj_float_over' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'obj_float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'obj_float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => false
            ],
            'obj_rounded_value' => [
                'value'    => 1345.001,
                'target'   => new Numeric(1345),
                'expected' => true
            ],
        ];
    }

    /**
     * @dataProvider equalsDataSet
     */
    public function testEquals($value, $target, $expected)
    {
        $numeric = new Numeric($value);

        $this->assertSame($expected, $numeric->equals($target));
    }

    public function equalsDataSet()
    {
        return [
            'over' => [
                'value'    => 457,
                'target'   => 456,
                'expected' => false
            ],
            'same' => [
                'value'    => 456,
                'target'   => 456,
                'expected' => true
            ],
            'under' => [
                'value'    => 455,
                'target'   => 456,
                'expected' => false
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => 12975.05,
                'expected' => false
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => 12975.05,
                'expected' => true
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => 1345.05,
                'expected' => false
            ],
            'rounded_value' => [
                'value'    => 1345.001,
                'target'   => 1345,
                'expected' => true
            ],
            'tolerance_string_over' => [
                'value'    => 2000,
                'target'   => '1345.01',
                'expected' => false
            ],
            'tolerance_string_under' => [
                'value'    => 563.09,
                'target'   => '564',
                'expected' => false
            ],
            'equals' => [
                'value'    => 710.06,
                'target'   => '710.06',
                'expected' => true
            ],
            'over' => [
                'value'    => 457,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'same' => [
                'value'    => 456,
                'target'   => new Integer(456),
                'expected' => true
            ],
            'under' => [
                'value'    => 455,
                'target'   => new Integer(456),
                'expected' => false
            ],
            'float_over' => [
                'value'    => 12975.06,
                'target'   => new Numeric(12975.05),
                'expected' => false
            ],
            'float_same' => [
                'value'    => 12975.05,
                'target'   => new Numeric(12975.05),
                'expected' => true
            ],
            'float_under' => [
                'value'    => 1345.04,
                'target'   => new Numeric(1345.05),
                'expected' => false
            ],
            'rounded_value' => [
                'value'    => 1345.001,
                'target'   => new Integer(1345),
                'expected' => true
            ],
        ];
    }
}
