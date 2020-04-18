<?php

use Clean\Value\Enum;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class EnumTest extends TestCase
{
    /**
     * @dataProvider validateDataset
     */
    public function testValidate($value, $hasError)
    {
        $enum = new class($value) extends Enum {
            const ENUM_VALUE1 = 100;
            const ENUM_VALUE2 = 200;
            const ENUM_VALUE3 = 300;
            const ENUM_VALUE4 = [400, 500];
        };

        $this->assertSame($hasError, $enum->hasErrors());
    }

    public function validateDataset()
    {
        return [
            'valid' => [
                'value'    => 100,
                'hasError' => false
            ],
            'invalid' => [
                'value'    => 1000,
                'hasError' => true
            ],
            'type' => [
                'value'    => '100',
                'hasError' => true
            ],
            'array' => [
                'value'    => [400, 500],
                'hasError' => false
            ],
            'invalid_array' => [
                'value'    => [100, 200],
                'hasError' => true
            ],
        ];
    }

    /**
     * @dataProvider displayDataset
     */
    public function testDisplay($value, $display)
    {
        $enum = new class($value) extends Enum {
            const ENUM_VALUE1 = 100;
            const ENUM_VALUE2 = 200;
            const ENUM_VALUE3 = [300];

            protected $displayMap = [
                'ENUM_VALUE1' => 'value1',
                'ENUM_VALUE2' => 'value2',
                'ENUM_VALUE3' => 'value3'
            ];
        };

        $this->assertSame($display, $enum->display());
    }

    public function displayDataset()
    {
        return [
            'valid' => [
                'value'    => 100,
                'hasError' => 'value1'
            ],
            'invalid' => [
                'value'    => 200,
                'hasError' => 'value2'
            ],
            'type' => [
                'value'    => [300],
                'hasError' => 'value3'
            ],
        ];
    }

    /**
     * @dataProvider isDataSet
     */
    public function testIs($value, $const, $expected)
    {
        $enum = new class($value) extends Enum {
            const ENUM_VALUE1 = 100;
            const ENUM_VALUE2 = 200;
            const ENUM_VALUE3 = 300;
            const ENUM_VALUE4 = [200];
        };

        $this->assertSame($expected, $enum->is($const));
    }

    public function isDataSet()
    {
        return [
            'valid' => [
                'value'    => 100,
                'const'    => 'ENUM_VALUE1',
                'expected' => true
            ],
            'invalid' => [
                'value'    => 400,
                'const'    => 'ENUM_VALUE1',
                'expected' => false
            ],
            'array' => [
                'value'    => [200],
                'const'    => 'ENUM_VALUE4',
                'expected' => true
            ],
        ];
    }
}
