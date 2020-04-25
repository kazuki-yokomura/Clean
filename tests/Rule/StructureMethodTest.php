<?php

use PHPUnit\Framework\TestCase;
use Clean\Rule\StructureMethod;

/**
 * rule method test
 */
class StructureMethodTest extends TestCase
{
    /**
     * @dataProvider isNotNullDataSet
     */
    public function testIsNotNull($value, $expected)
    {
        $this->assertSame($expected, StructureMethod::isNotNull($value, ['key' => 'key']));
    }

    public function isNotNullDataSet()
    {
        return [
            'null' => [
                'value' => [
                    'key' => null
                ],
                'expexted' => false
            ],
            'not_null' => [
                'value' => [
                    'key' => false
                ],
                'expexted' => true
            ]
        ];
    }

    /**
     * @dataProvider isAllowKeysDataSet
     */
    public function testIsAllowKeys($value, $expected)
    {
        $this->assertSame($expected, StructureMethod::isAllowKeys($value, ['keys' => range(0, 10)]));
    }

    public function isAllowKeysDataSet()
    {
        return [
            'valid' => [
                'value'    => range(1, 10),
                'expected' => true
            ],
            'min_range' => [
                'value'    => range(1, 10, 2),
                'expected' => true
            ],
            'string' => [
                'value'    => [
                    '1' => 'hoge',
                    '2' => new \stdClass(),
                    '3' => false
                ],
                'expected' => true
            ],
            'invalid' => [
                'value'    => [11 => 11],
                'expected' => false
            ],
        ];
    }
}
