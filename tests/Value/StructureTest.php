<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Value\Structure;

/**
 *
 */
class StructureTest extends TestCase
{
    /**
     * @dataProvider canInputDataset
     */
    public function testCanInput($value, $hasError)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'integer1' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'lessOr'      => 'integer2'
                ],
                'integer2' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ],
                'text' => [
                    'valueObject' => 'ValueValidator\Value\Text',
                ],
                'url' => [
                    'valueObject' => 'ValueValidator\Value\Url',
                    'nullable'    => true
                ]
            ];
        };

        $this->assertSame($hasError, $structure->hasErrors());
    }

    public function canInputDataset()
    {
        return [
            'valid' => [
                'value' => [
                    'integer1' => 12345,
                    'integer2' => 23456,
                    'text'     => 'hogehoge',
                    'url'      => 'https://www.php.net/manual/ja/function.bin2hex.php'
                ],
                'hasError' => false
            ],
            'error' => [
                'value' => [
                    'integer1' => 23457,
                    'integer2' => 23456,
                    'text'     => 'hogehoge',
                    'url'      => 'https://www.php.net/manual/ja/function.bin2hex.php'
                ],
                'hasError' => true
            ],
            'same' => [
                'value' => [
                    'integer1' => 23456,
                    'integer2' => 23456,
                    'text'     => 'hogehoge',
                    'url'      => 'https://www.php.net/manual/ja/function.bin2hex.php'
                ],
                'hasError' => false
            ],
            'nullable' => [
                'value' => [
                    'integer1' => 23456,
                    'integer2' => 23456,
                    'text'     => 'hogehoge',
                ],
                'hasError' => false
            ],
            'cant_null' => [
                'value' => [
                    'integer1' => 23456,
                    'integer2' => 23456,
                ],
                'hasError' => true
            ],
            'value_error' => [
                'value' => [
                    'integer1' => 'hogehoge',
                    'integer2' => 23456,
                    'text'     => 'hogehoge',
                ],
                'hasError' => true
            ],
            'value_error_compression' => [
                'value' => [
                    'integer1' => 23456,
                    'integer2' => 'hoge',
                    'text'     => 'hogehoge',
                ],
                'hasError' => true
            ],
        ];
    }

    /**
     * @dataProvider moreThanDataSet
     */
    public function testMoreThan($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'moreThan'    => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreThanDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 10000,
                    'higher' => 20000
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 20000,
                    'higher' => 20000
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'under' => [
                'value' => [
                    'lower'  => 20001,
                    'higher' => 20000
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 2,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider moreThanFloatDataSet
     */
    public function testMoreThanFloat($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'moreThan'    => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreThanFloatDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 111111.05,
                    'higher' => 231511.05
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 10067.234,
                    'higher' => 10067.234
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'under' => [
                'value' => [
                    'lower'  => 100.04,
                    'higher' => 100.0
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20.05
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 20.05,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider moreThanFloatIntsDataSet
     */
    public function testMoreThanFloatInt($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'moreThan'    => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreThanFloatIntsDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 2000.0,
                    'higher' => 20000
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 300.0,
                    'higher' => 300
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'under' => [
                'value' => [
                    'lower'  => 100.0,
                    'higher' => 10
                ],
                'errors' => [
                    'higher' => ['moreThan' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 20.05,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessThanDataSet
     */
    public function testLessThan($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'lessThan'    => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessThanDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 123909105812,
                    'higher' => 123909105813
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 787878,
                    'higher' => 787878
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'over' => [
                'value' => [
                    'lower'  => 20001,
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessThanFloatDataSet
     */
    public function testLessThanFloat($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'lessThan'    => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessThanFloatDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 34.5,
                    'higher' => 34.51
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 12.56,
                    'higher' => 12.56
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'over' => [
                'value' => [
                    'lower'  => 34.125,
                    'higher' => 34.1
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 3.14125
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2.5,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessThanFloatIntDataSet
     */
    public function testLessThanFloatInt($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'lessThan'    => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessThanFloatIntDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 33.99,
                    'higher' => 34
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 12.0,
                    'higher' => 12
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'over' => [
                'value' => [
                    'lower'  => 34.125,
                    'higher' => 34
                ],
                'errors' => [
                    'lower' => ['lessThan' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 3
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2.9,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider moreOrDataSet
     */
    public function testMoreOr($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'moreOr'      => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreOrDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 10000,
                    'higher' => 20000
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 20000,
                    'higher' => 20000
                ],
                'errors' => []
            ],
            'under' => [
                'value' => [
                    'lower'  => 20001,
                    'higher' => 20000
                ],
                'errors' => [
                    'higher' => ['moreOr' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 2,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider moreOrFloatDataSet
     */
    public function testMoreOrFloat($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'moreOr'      => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreOrFloatDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 111111.05,
                    'higher' => 231511.05
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 10067.234,
                    'higher' => 10067.234
                ],
                'errors' => []
            ],
            'under' => [
                'value' => [
                    'lower'  => 100.01,
                    'higher' => 100.0
                ],
                'errors' => [
                    'higher' => ['moreOr' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20.05
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 20.05,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider moreOrFloatIntDataSet
     */
    public function testMoreOrFloatInt($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'moreOr'      => 'lower',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function moreOrFloatIntDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 2000.0,
                    'higher' => 20000
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 300.0,
                    'higher' => 300
                ],
                'errors' => []
            ],
            'under' => [
                'value' => [
                    'lower'  => 100.0,
                    'higher' => 10
                ],
                'errors' => [
                    'higher' => ['moreOr' => '']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 20
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 20.05,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessOrDataSet
     */
    public function testLessOr($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'lessOr'      => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessOrDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 123909105812,
                    'higher' => 123909105813
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 787878,
                    'higher' => 787878
                ],
                'errors' => []
            ],
            'over' => [
                'value' => [
                    'lower'  => 20001,
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['lessOr' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => null,
                    'higher' => 20000
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2,
                    'higher' => 'two'
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessOrFloatDataSet
     */
    public function testLessOrFloat($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'lessOr'      => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessOrFloatDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 34.5,
                    'higher' => 34.51
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 12.56,
                    'higher' => 12.56
                ],
                'errors' => []
            ],
            'over' => [
                'value' => [
                    'lower'  => 34.125,
                    'higher' => 34.1
                ],
                'errors' => [
                    'lower' => ['lessOr' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => 'hoge',
                    'higher' => 3.14125
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2.5,
                    'higher' => null
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider lessOrFloatIntDataSet
     */
    public function testLessOrFloatInt($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'lessOr'      => 'higher',
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function lessOrFloatIntDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'lower'  => 33.99,
                    'higher' => 34
                ],
                'errors' => []
            ],
            'same' => [
                'value' => [
                    'lower'  => 12.0,
                    'higher' => 12
                ],
                'errors' => []
            ],
            'over' => [
                'value' => [
                    'lower'  => 34.125,
                    'higher' => 34
                ],
                'errors' => [
                    'lower' => ['lessOr' => '']
                ]
            ],
            'self_error' => [
                'value' => [
                    'lower'  => new stdClass(),
                    'higher' => 3
                ],
                'errors' => [
                    'lower' => ['invalid' => 'Not numeric value.']
                ]
            ],
            'target_error' => [
                'value' => [
                    'lower'  => 2.9,
                    'higher' => [1]
                ],
                'errors' => [
                    'higher' => ['invalid' => 'Not numeric value.']
                ]
            ],
        ];
    }

    /**
     * @dataProvider nullableComparisonDataSet
     */
    public function testNullableComparison($value, $errors)
    {
        $structure = new class($value) extends Structure {
            protected $scheme = [
                'lower' => [
                    'valueObject' => 'ValueValidator\Value\Integer',
                    'lessOr'      => 'higher',
                    'nullable'    => true
                ],
                'higher' => [
                    'valueObject' => 'ValueValidator\Value\Numeric',
                    'nullable'    => true
                ]
            ];
        };

        $this->assertSame($errors, $structure->getErrors());
    }

    public function nullableComparisonDataSet()
    {
        return [
            'both_null' => [
                'both_null' => [
                    'lower'  => null,
                    'higher' => null
                ],
                'errors' => []
            ],
            'target_null' => [
                'both_null' => [
                    'lower'  => 234,
                    'higher' => null
                ],
                'errors' => []
            ],
            'self_null' => [
                'both_null' => [
                    'lower'  => null,
                    'higher' => 5.6
                ],
                'errors' => []
            ],
        ];
    }
}
