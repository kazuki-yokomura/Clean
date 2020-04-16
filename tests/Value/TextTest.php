<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\Text;

/**
 * rule method test
 */
class TextTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputDataSet
     */
    public function testCanInput($value, $hasError)
    {
        $text = new Text($value);
        $this->assertSame($hasError, $text->hasErrors());
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
     * @dataProvider getDataSet
     */
    public function testGet($value)
    {
        $text = new Text($value);
        $this->assertSame($value, $text->get());
    }

    /**
     * @test
     * @dataProvider getDataSet
     */
    public function testGetOriginal($value)
    {
        $text = new Text($value);
        $this->assertSame($value, $text->getOriginal());
    }

    /**
     * @test
     * @dataProvider getDataSet
     */
    public function testToString($value)
    {
        $text = new Text($value);
        $this->assertSame($value, (string)$text);
    }

    /**
     * test get method
     */
    public function getDataSet()
    {
        return [
            'valid'    => ['hogehoge'],
            'integer'  => ['1234567890'],
            'space'    => [" \n "],
            'to_long'  => [str_repeat('longlonglo', 100)],
            'unicode'  => ['\u6211\u597d'],
            'sequence' => ["\xE2\x98\x9D\x28\x20\xE2\x97\xA0\xE2\x80\xBF\xE2\x97\xA0\x20\x29\xE2\x98\x9D"]
        ];
    }

    /**
     * @test
     * @dataProvider rangeDataSet
     */
    public function testRange($value, $__testRange, $hasError)
    {
        $text = new class($value, $__testRange) extends Text {
            public function __construct($value, $__testRange)
            {
                $this->minCharacters = $__testRange['min'];
                $this->maxCharacters = $__testRange['max'];
                parent::__construct($value);
            }
        };

        $this->assertSame($hasError, $text->hasErrors());
    }

    /**
     * testRange dataset
     */
    public function rangeDataSet()
    {
        return [
            'valid' => [
                'value'    => 'hogehgoe',
                'range'    => ['min' => 0, 'max' => 10],
                'hasError' => false
            ],
            'fixed' => [
                'value'    => 'hogehgoe',
                'range'    => ['min' => 8, 'max' => 8],
                'hasError' => false
            ],
            'fixed_under' => [
                'value'    => 'hogehgo',
                'range'    => ['min' => 8, 'max' => 8],
                'hasError' => true
            ],
            'fixed_over' => [
                'value'    => 'hogehgoeh',
                'range'    => ['min' => 8, 'max' => 8],
                'hasError' => true
            ],
            'zero_skip' => [
                'value'    => 'hogehgoeh',
                'range'    => ['min' => 0, 'max' => 0],
                'hasError' => false
            ],
            'empty' => [
                'value'    => '',
                'range'    => ['min' => 0, 'max' => 1],
                'hasError' => false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider rangeDataSet
     */
    public function testInvoke($value, $__testRange, $hasError)
    {
        $text = new class('oldString', $__testRange) extends Text {
            public function __construct($value, $__testRange)
            {
                $this->minCharacters = $__testRange['min'];
                $this->maxCharacters = $__testRange['max'];
                parent::__construct($value);
            }
        };

        $newText = $text($value, $__testRange);

        $this->assertSame($hasError, $newText->hasErrors());
    }
}
