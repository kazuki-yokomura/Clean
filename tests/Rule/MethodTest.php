<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Rule\Method;

/**
 * rule method test
 */
class MethodTest extends TestCase
{
    protected $values;

    protected function setUp()
    {
        parent::setUp();
        $this->values = [
            'integer'     => 123,
            'minus'       => -123,
            'float'       => 1.23,
            'minusFloat'  => -1.23,
            'string'      => 'string',
            'array'       => [1,2,3],
            'mixedArray'  => [1,'two',3],
            'nestedArray' => [1 => [2 => 3]],
            'object'      => new stdClass(),
            'json'        => '{"key": "value", "key2": 3}',
            'bool'        => true,
            'null'        => null
        ];
    }

    /**
     * @test
     * @dataProvider scalarDataSet
     */
    public function testIsScalar($type, $expected)
    {
        $result = Method::isScalar($this->values[$type]);
        $this->assertSame($expected, $result);
    }

    /**
     * is_scalar test
     *
     * @return array
     */
    public function scalarDataSet()
    {
        return [
            'integer' => [
                'type'     => 'integer',
                'expected' => true
            ],
            'minus' => [
                'type'     => 'minus',
                'expected' => true
            ],
            'float' => [
                'type'     => 'float',
                'expected' => true
            ],
            'minusFloat' => [
                'type'     => 'minusFloat',
                'expected' => true
            ],
            'string' => [
                'type'     => 'string',
                'expected' => true
            ],
            'array' => [
                'type'     => 'array',
                'expected' => false
            ],
            'mixedArray' => [
                'type'     => 'mixedArray',
                'expected' => false
            ],
            'nestedArray' => [
                'type'     => 'nestedArray',
                'expected' => false
            ],
            'object' => [
                'type'     => 'object',
                'expected' => false
            ],
            'json' => [
                'type'     => 'json',
                'expected' => true
            ],
            'bool' => [
                'type'     => 'bool',
                'expected' => true
            ],
            'null' => [
                'type'     => 'null',
                'expected' => false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider numericDataSet
     */
    public function testIsNumeric($type, $expected)
    {
        $result = Method::isNumeric($this->values[$type]);
        $this->assertSame($expected, $result);
    }

    /**
     * is_numeric test
     *
     * @return array
     */
    public function numericDataSet()
    {
        return [
            'integer' => [
                'type'     => 'integer',
                'expected' => true
            ],
            'minus' => [
                'type'     => 'minus',
                'expected' => true
            ],
            'float' => [
                'type'     => 'float',
                'expected' => true
            ],
            'minusFloat' => [
                'type'     => 'minusFloat',
                'expected' => true
            ],
            'string' => [
                'type'     => 'string',
                'expected' => false
            ],
            'array' => [
                'type'     => 'array',
                'expected' => false
            ],
            'mixedArray' => [
                'type'     => 'mixedArray',
                'expected' => false
            ],
            'nestedArray' => [
                'type'     => 'nestedArray',
                'expected' => false
            ],
            'object' => [
                'type'     => 'object',
                'expected' => false
            ],
            'json' => [
                'type'     => 'json',
                'expected' => false
            ],
            'bool' => [
                'type'     => 'bool',
                'expected' => false
            ],
            'null' => [
                'type'     => 'null',
                'expected' => false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider minCharactersDataSet
     */
    public function testMinCharacters($value, $option, $expected)
    {
        $result = Method::minCharacters($value, $option);
        $this->assertSame($expected, $result);
    }

    /**
     * min_charactes test
     *
     * @return array
     */
    public function minCharactersDataSet()
    {
        return [
            'valid' => [
                'value'    => 'hogehoge',
                'option'   => ['min' => 7],
                'expected' => true
            ],
            'same' => [
                'value'    => 'hogehoge',
                'option'   => ['min' => 8],
                'expected' => true
            ],
            'under' => [
                'value'    => 'hogehoge',
                'option'   => ['min' => 9],
                'expected' => false
            ],
            'mb_same' => [
                'value'    => 'アいウえオ',
                'option'   => ['min' => 5],
                'expected' => true
            ],
            'mb_under' => [
                'value'    => 'アいウえオ',
                'option'   => ['min' => 6],
                'expected' => false
            ],
            'in_br_same' => [
                'value'    => <<<EOF
break
line.
EOF,
                'option'   => ['min' => 11],
                'expected' => true
            ],
        ];
    }

    /**
     * @test
     * @dataProvider maxCharactersDataSet
     */
    public function testMaxCharacters($value, $option, $expected)
    {
        $result = Method::maxCharacters($value, $option);
        $this->assertSame($expected, $result);
    }

    /**
     * min_charactes test
     *
     * @return array
     */
    public function maxCharactersDataSet()
    {
        return [
            'valid' => [
                'value'    => 'hogehoge',
                'option'   => ['max' => 9],
                'expected' => true
            ],
            'same' => [
                'value'    => 'hogehoge',
                'option'   => ['max' => 8],
                'expected' => true
            ],
            'over' => [
                'value'    => 'hogehoge',
                'option'   => ['max' => 7],
                'expected' => false
            ],
            'mb_same' => [
                'value'    => 'アいウえオ',
                'option'   => ['max' => 5],
                'expected' => true
            ],
            'mb_over' => [
                'value'    => 'アいウえオ',
                'option'   => ['max' => 4],
                'expected' => false
            ],
            'in_br_same' => [
                'value'    => <<<EOF
break
line.
EOF,
                'option'   => ['min' => 11],
                'expected' => true
            ],
        ];
    }

    /**
     * @test
     * @dataProvider minValueDataSet
     */
    public function testMinValue($value, $option, $expected)
    {
        $result = Method::minValue($value, $option);
        $this->assertSame($expected, $result);
    }

    /**
     * min_value test
     *
     * @return array
     */
    public function minValueDataSet()
    {
        return [
            'valid' => [
                'value'    => 10,
                'option'   => ['min' => 9],
                'expected' => true
            ],
            'same' => [
                'value'    => 10,
                'option'   => ['min' => 10],
                'expected' => true
            ],
            'under' => [
                'value'    => 10,
                'option'   => ['min' => 11],
                'expected' => false
            ],
            'float_valid' => [
                'value'    => -10.05,
                'option'   => ['min' => -11],
                'expected' => true
            ],
            'float_same' => [
                'value'    => -10.05,
                'option'   => ['min' => -10.05],
                'expected' => true
            ],
            'float_under' => [
                'value'    => -10.05,
                'option'   => ['min' => -10],
                'expected' => false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider maxValueDataSet
     */
    public function testMaxValue($value, $option, $expected)
    {
        $result = Method::maxValue($value, $option);
        $this->assertSame($expected, $result);
    }

    /**
     * min_value test
     *
     * @return array
     */
    public function maxValueDataSet()
    {
        return [
            'valid' => [
                'value'    => 10,
                'option'   => ['max' => 11],
                'expected' => true
            ],
            'same' => [
                'value'    => 10,
                'option'   => ['max' => 10],
                'expected' => true
            ],
            'over' => [
                'value'    => 10,
                'option'   => ['max' => 9],
                'expected' => false
            ],
            'float_valid' => [
                'value'    => -10.05,
                'option'   => ['max' => -10.04],
                'expected' => true
            ],
            'float_same' => [
                'value'    => -10.05,
                'option'   => ['max' => -10.05],
                'expected' => true
            ],
            'float_over' => [
                'value'    => -10.05,
                'option'   => ['max' => -10.06],
                'expected' => false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider pregMatchDataSet
     */
    public function testPregMatch($value, $pattern, $expected)
    {
        $result = Method::pregMatch($value, compact('pattern'));
        $this->assertSame($expected, $result);
    }

    /**
     * pregMatchDataSet
     */
    public function pregMatchDataSet()
    {
        return [
            'valid' => [
                'value'    => 'all ready.',
                'pattern'  => '/^[a-z- .]+\.$/',
                'expected' => true
            ],
            'invalid' => [
                'value'    => 'All ready.',
                'pattern'  => '/^[a-z- .]+\.$/',
                'expected' => false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider canJsonEncodeDataSet
     */
    public function testCanJsonEncode($value, $option, $expected)
    {
        $result = Method::canJsonEncode($value, compact('option'));
        $this->assertSame($expected, $result);
    }

    /**
     * canJsonEncodeDataSet
     */
    public function canJsonEncodeDataSet()
    {
        return [
            'valid' => [
                'value'    => ['json' => 'json'],
                'option'   => 0,
                'expected' => true
            ],
            'invalid' => [
                'value'    => "\xB1\x31",
                'option'   => 0,
                'expected' => false
            ],
            'add_option' => [
                'value'    => "\xB1\x31",
                'option'   => JSON_PARTIAL_OUTPUT_ON_ERROR,
                'expected' => true
            ],
        ];
    }
}
