<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Value\Pattern;

/**
 * pattern test
 */
class PatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputDataSet
     */
    public function testCanInput($value, $hasError)
    {
        $pattern = new class($value) extends Pattern {
            protected $pattern = '/.+/';
            public function __construct($value)
            {
                parent::__construct($value);
            }
        };
        $this->assertSame($hasError, $pattern->hasErrors());
    }

    /**
     * is_scalar test
     *
     * @return array
     */
    public function inputDataSet()
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
                'hasError' => false
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
                'hasError' => false
            ],
            'bool' => [
                'value'    => true,
                'hasError' => false
            ],
            'null' => [
                'value'    => null,
                'hasError' => true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider patternDataset
     */
    public function testPattern($value, $regex, $hasError)
    {
        $pattern = new class($value, $regex) extends Pattern {
            public function __construct($value, $regex)
            {
                $this->pattern = $regex;
                parent::__construct($value);
            }
        };

        $this->assertSame($hasError, $pattern->hasErrors());
    }

    /**
     * testPattern dataset
     */
    public function patternDataset()
    {
        return [
            'alphabet' => [
                'value'    => 'abcdefg',
                'pattern'  => '/^[a-z]+$/',
                'hasError' => false
            ],
            'alphabet_error' => [
                'value'    => 'abcdefg0',
                'pattern'  => '/^[a-z]+$/',
                'hasError' => true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider textInheritanceDataset
     */
    public function testTextInheritance($value, $option, $hasError)
    {
        $pattern = new class($value, $option) extends Pattern {
            protected $pattern = '/^[a-z]+$/';

            public function __construct($value, $option)
            {
                $this->minCharacters = $option['min'];
                $this->maxCharacters = $option['max'];
                parent::__construct($value);
            }
        };

        $this->assertSame($hasError, $pattern->hasErrors());
    }

    /**
     * testTextInheritance dataset
     */
    public function textInheritanceDataset()
    {
        return [
            'valid' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 0,
                    'max' => 10
                ],
                'hasError' => false
            ],
            'same_max' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 0,
                    'max' => 7
                ],
                'hasError' => false
            ],
            'same_min' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 7,
                    'max' => 10
                ],
                'hasError' => false
            ],
            'same' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 7,
                    'max' => 7
                ],
                'hasError' => false
            ],
            'under' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 8,
                    'max' => 10
                ],
                'hasError' => true
            ],
            'over' => [
                'value'    => 'abcdefg',
                'option'   => [
                    'min' => 0,
                    'max' => 5
                ],
                'hasError' => true
            ]
        ];
    }
}
