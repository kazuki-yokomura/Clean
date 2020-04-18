<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\Uuid;

/**
 * pattern test
 */
class UuidTest extends TestCase
{
    /**
     * @dataProvider uuidDataset
     */
    public function testUuid($value, $hasError)
    {
        $pattern = new Uuid($value);

        $this->assertSame($hasError, $pattern->hasErrors());
    }

    public function uuidDataset()
    {
        return [
            'v4' => [
                'value'    => self::uuidv4(),
                'hasError' => false
            ],
            'invalid' => [
                'value'    => '53067030-1c6a-50a1-8dc4-568785cbdbd3',
                'hasError' => true
            ]
        ];
    }

    /**
     * create uuid v4
     */
    public static function uuidv4()
    {
        $section = [];
        $section[] = mt_rand(0, 0xffff);
        $section[] = mt_rand(0, 0xffff);
        $section[] = mt_rand(0, 0xffff);
        $section[] = mt_rand(0, 0x0fff) | 0x4000;
        $section[] = mt_rand(0, 0x3fff) | 0x8000;
        $section[] = mt_rand(0, 0xffff);
        $section[] = mt_rand(0, 0xffff);
        $section[] = mt_rand(0, 0xffff);

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', ...$section);
    }
}
