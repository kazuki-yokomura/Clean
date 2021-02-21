<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Rule\FileMethod;

/**
 * rule method test
 */
class FileMethodTest extends TestCase
{
    /**
     * @dataProvider isFileDataSet
     */
    public function testIsFile($value, $expected)
    {
        $this->assertSame($expected, FileMethod::isFile($value));
    }

    public function isFileDataSet()
    {
        return [
            'valid' => [
                'value' => [
                    'name'     => 'example.jpg',
                    'type'     => 'image/jpeg',
                    'size'     => 571127,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0
                ],
                'expected' => true
            ],
            'extra' => [
                'value' => [
                    'name'     => 'example.jpg',
                    'type'     => 'image/jpeg',
                    'size'     => 571127,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0,
                    'hoge'
                ],
                'expected' => false
            ],
            'error_few' => [
                'value' => [
                    'name'     => 'example.jpg',
                    'type'     => 'image/jpeg',
                    'size'     => 571127,
                    'tmp_name' => 'phpGHxS',
                ],
                'expected' => false
            ],
            'name_few' => [
                'value' => [
                    'type'     => 'image/jpeg',
                    'size'     => 571127,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0,
                ],
                'expected' => false
            ],
            'type_few' => [
                'value' => [
                    'name'     => 'example.jpg',
                    'size'     => 571127,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0,
                ],
                'expected' => false
            ],
            'not_array' => [
                'value' => 'string',
                'expected' => false
            ]
        ];
    }

    /**
     * @dataProvider hasUploadErrorDataSet
     */
    public function testHasUploadError($errorNum, $expected)
    {
        $info = [
            'name'     => 'example.jpg',
            'type'     => 'image/jpeg',
            'size'     => 571127,
            'tmp_name' => 'phpGHxS',
            'error'    => $errorNum
        ];
        $this->assertSame($expected, FileMethod::hasUploadError($info));
    }

    public function hasUploadErrorDataSet()
    {
        return [
            'valid' => [
                'errorNum' => 0,
                'expected' => true
            ],
            'has_error' => [
                'errorNum' => UPLOAD_ERR_NO_TMP_DIR,
                'expected' => false
            ],
            'has_another_error' => [
                'errorNum' => 11,
                'expected' => false
            ],
        ];
    }

    /**
     * @dataProvider maxNameLengthDataSet
     */
    public function testMaxNameLength($name, $length, $expected)
    {
        $info = [
            'name'     => $name,
            'type'     => 'image/jpeg',
            'size'     => 571127,
            'tmp_name' => 'phpGHxS',
            'error'    => 0
        ];
        $this->assertSame($expected, FileMethod::maxNameLength($info, ['maxCharacters' => $length]));
    }

    public function maxNameLengthDataSet()
    {
        return [
            'valid' => [
                'name'     => 'hogehoge',
                'length'   => 50,
                'expected' => true
            ],
            'same' => [
                'name'     => str_repeat('a', 50),
                'length'   => 50,
                'expected' => true
            ],
            'over' => [
                'name'     => str_repeat('a', 51),
                'length'   => 50,
                'expected' => false
            ],
            'mb_same' => [
                'name'     => str_repeat('あ', 25),
                'length'   => 25,
                'expected' => true
            ],
            'mb_over' => [
                'name'     => str_repeat('あ', 26),
                'length'   => 25,
                'expected' => false
            ],
        ];
    }

    /**
     * @dataProvider maxBytesDataSet
     */
    public function testMaxBytes($size, $max, $expected)
    {
        $info = [
            'name'     => 'hoge.jpg',
            'type'     => 'image/jpeg',
            'size'     => $size,
            'tmp_name' => 'phpGHxS',
            'error'    => 0
        ];
        $this->assertSame($expected, FileMethod::maxBytes($info, ['maxBytes' => $max]));
    }

    public function maxBytesDataSet()
    {
        define('KB', 1024);
        define('MB', KB * 1024);
        define('GB', MB * 1024);

        return [
            'valid' => [
                'size'     => 600 * KB,
                'max'      => 100 * MB,
                'expected' => true
            ],
            'same' => [
                'size'     => 600 * MB,
                'max'      => 600 * MB,
                'expected' => true
            ],
            'over' => [
                'size'     => 600 * GB + 1,
                'max'      => 600 * GB,
                'expected' => false
            ],
        ];
    }

    /**
     * @dataProvider allowExtensionsDataSet
     */
    public function testAllowExtensions($name, $extensions, $expected)
    {
        $info = [
            'name'     => $name,
            'type'     => 'image/jpeg',
            'size'     => 571127,
            'tmp_name' => 'phpGHxS',
            'error'    => 0
        ];
        $this->assertSame($expected, FileMethod::allowExtensions($info, ['allowExtensions' => $extensions]));
    }

    public function allowExtensionsDataSet()
    {
        return [
            'valid' => [
                'name' => 'hoge.jpeg',
                'extensions' => [
                    'jpg',
                    'png',
                    'jpeg',
                    'gif'
                ],
                'expected' => true
            ],
            'not_extension' => [
                'name' => 'hoge',
                'extensions' => [
                    'hoge'
                ],
                'expected' => false
            ],
            'denied' => [
                'name' => 'hoge.tiff',
                'extensions' => [
                    'jpg',
                    'png',
                    'jpeg',
                    'gif'
                ],
                'expected' => false
            ],
            'mb_name' => [
                'name' => '私.png',
                'extensions' => [
                    'jpg',
                    'png',
                    'jpeg',
                    'gif'
                ],
                'expected' => true
            ],
            'multi_dot' => [
                'name' => 'a.b.c.gif',
                'extensions' => [
                    'jpg',
                    'png',
                    'jpeg',
                    'gif'
                ],
                'expected' => true
            ],
        ];
    }

    /**
     * @dataProvider allowMimeTypesDataSet
     */
    public function testAllowMimeTypes($type, $allow, $expected)
    {
        $info = [
            'name'     => 'hoge.jpeg',
            'type'     => $type,
            'size'     => 571127,
            'tmp_name' => 'phpGHxS',
            'error'    => 0
        ];
        $this->assertSame($expected, FileMethod::allowMimeTypes($info, ['allowMimeTypes' => $allow]));
    }

    public function allowMimeTypesDataSet()
    {
        return [
            'valid' => [
                'type' => 'image/jpeg',
                'allow' => [
                    'image/jpeg',
                    'image/png',
                ],
                'expected' => true
            ],
            'invalid' => [
                'type' => 'image/gif',
                'allow' => [
                    'image/jpeg',
                    'image/png',
                ],
                'expected' => false
            ],
        ];
    }
}
