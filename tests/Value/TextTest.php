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
}
