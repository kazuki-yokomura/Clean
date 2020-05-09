<?php

use Clean\Value\File;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class FileTest extends TestCase
{
    /**
     * @dataProvider validateDataset
     */
    public function testValidate($value, $errors)
    {
        $file = new class($value) extends File {
            protected $maxBytes = 600 * 1024 * 1024;
            protected $maxNameLength = 50;
            protected $allowExtensions = [
                'gif', 'png',
                'jpg', 'jpeg',
                'svg', 'giff',
            ];
            protected $allowMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/giff',
                'image/svg',
                'image/svg+xml'
            ];
        };

        $this->assertSame($errors, $file->getErrors());
    }

    public function validateDataset()
    {
        $maxBytes = 600 * 1024 * 1024;
        $overBytes = 600 * 1024 * 1024 + 1;

        return [
            'valid' => [
                'value'    => [
                    'name'     => 'example.jpg',
                    'type'     => 'image/jpeg',
                    'size'     => 500 * 1024 * 1024,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0
                ],
                'errors' => []
            ],
            'invalid_value' => [
                'value'  => 'string',
                'errors' => [
                    'isFile' => 'Is not upload file.'
                ]
            ],
            'has_error' => [
                'value'    => [
                    'name'     => null,
                    'type'     => null,
                    'size'     => null,
                    'tmp_name' => null,
                    'error'    => UPLOAD_ERR_INI_SIZE
                ],
                'errors' => [
                    'uploadError' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini'
                ]
            ],
            'otherError' => [
                'value' => [
                    'name'     => str_repeat('a', 47) . '.txt',
                    'type'     => 'text/plain',
                    'size'     => $overBytes,
                    'tmp_name' => 'phpGHxS',
                    'error'    => 0
                ],
                'errors' => [
                    'maxNameLength'   => 'Can\'t use 51 characters name. Can use short to 50 characters',
                    'maxBytes'        => sprintf('Maximam size is %s. Can\'t input %s.', number_format($maxBytes), number_format($overBytes)),
                    'allowExtensions' => str_repeat('a', 47) . '.txt has invalid extensions.',
                    'allowMimeTypes'  => 'text/plain is invalid type.'
                ]
            ]
        ];
    }
}
