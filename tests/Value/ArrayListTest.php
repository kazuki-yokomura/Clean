<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\ArrayList;

/**
 *
 */
class ArrayListTest extends TestCase
{
    public function testCanInput()
    {
        $value = [
            23, 45, 35,
            13, -56, 67,
            78, 67, 99
        ];

        $arrayList = new class($value) extends ArrayList {
            protected $valueClass = '\Clean\Value\Integer';
        };

        $this->assertSame(false, $arrayList->hasErrors());

        $expected = [];
        foreach ($value as $key => $v) {
            $expected[$key] = new \Clean\Value\Integer($v);
        }

        $this->assertEquals($expected, $arrayList->get());
    }

    /**
     * @dataProvider allowKeysDataSet
     */
    public function testAllowKeys($value, $hasError)
    {
        $arrayList = new class($value) extends ArrayList {
            protected $valueClass = '\Clean\Value\Integer';

            protected function setAllowKeys(): void
            {
                $this->allowKeys = range(0, 10);
            }
        };

        $this->assertSame($hasError, $arrayList->hasErrors());
    }

    public function allowKeysDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    10, 2, 456, 123, 123,
                    567, 1, 135, 12, 999,
                    12378075
                ],
                'hasError' => false
            ],
            'small' => [
                'value' => [
                    10, 2, 456, 123, 123
                ],
                'hasError' => false
            ],
            'stepping' => [
                'value' => [
                    1  => 10,
                    3  => 90,
                    5  => 40,
                    7  => 20,
                    10 => 40,
                ],
                'hasError' => false
            ],
            'string' => [
                'value' => [
                    '1'  => 20,
                    '3'  => 30,
                    '5'  => 60,
                    '7'  => 10,
                    '10' => 90,
                ],
                'hasError' => false
            ],
            'invalid' => [
                'value' => [
                    99 => 99
                ],
                'hasError' => true
            ],
        ];
    }

    /**
     * @dataProvider getErrorsDataSet
     */
    public function testGetErrors($value, $errors)
    {
        $arrayList = new class($value) extends ArrayList {
            protected $valueClass = '\Clean\Value\Numeric';

            protected function setAllowKeys(): void
            {
                $this->allowKeys = range(0, 10);
            }
        };

        $this->assertSame($errors, $arrayList->getErrors());
    }

    public function getErrorsDataSet()
    {
        return [
            'notArray' => [
                'value'  => null,
                'errors' => [
                    'invalid' => 'Not array.'
                ]
            ],
            'allowKeyError' => [
                'value'  => [99 => 1],
                'errors' => [
                    'isAllowKeys' => 'invalid keys.'
                ]
            ],
            'valueError' => [
                'value'  => [
                    1,
                    2,
                    'hoge',
                    5
                ],
                'errors' => [
                    2 => [
                        'invalid' => 'Not numeric value.'
                    ]
                ]
            ],
        ];
    }

    public function testIterator()
    {
        $value = [
            'one'   => 'hoge',
            'two'   => 'fuga',
            'three' => 'baz',
            'four'  => 'bee',
            'five'  => 'boo'
        ];

        $arrayList = new class($value) extends ArrayList {
            protected $valueClass = '\Clean\Value\Text';
        };

        foreach ($arrayList as $key => $valueObject) {
            $this->assertEquals(new \Clean\Value\Text($value[$key]), $valueObject);
        }
    }

    public function testArrayAccess()
    {
        $value = [
            'one'   => 'hoge',
            'two'   => 'fuga',
            'three' => 'baz',
            'four'  => 'bee',
            'five'  => 'boo'
        ];

        $arrayList = new class($value) extends ArrayList {
            protected $valueClass = '\Clean\Value\Text';
        };

        $this->assertEquals(new \Clean\Value\Text('baz'), $arrayList['three']);
    }
}
