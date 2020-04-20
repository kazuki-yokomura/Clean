<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\Structure;

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
            protected function setScheme(): void
            {
                $this->scheme = [
                    'integer1' => [
                        'valueObject' => 'Clean\Value\Integer',
                        'lessOr'      => 'integer2'
                    ],
                    'integer2' => [
                        'valueObject' => 'Clean\Value\Integer',
                    ],
                    'text' => [
                        'valueObject' => 'Clean\Value\Text',
                    ],
                    'url' => [
                        'valueObject' => 'Clean\Value\Url',
                        'nullable'    => true
                    ]
                ];
            }
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
}
