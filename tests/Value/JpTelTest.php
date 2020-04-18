<?php

use PHPUnit\Framework\TestCase;
use Clean\Value\JpTel;

/**
 * pattern test
 */
class JpTelTest extends TestCase
{
    /**
     * @dataProvider jpTelDataset
     */
    public function testJpTel($value, $hasError)
    {
        $pattern = new JpTel($value);

        $this->assertSame($hasError, $pattern->hasErrors());
    }

    public function jpTelDataset()
    {
        return [
            'free-' => [
                'value'    => '0120-922-299',
                'hasError' => false
            ],
            'free' => [
                'value'    => '0120922299',
                'hasError' => false
            ],
            'phone-' => [
                'value'    => '08043754356',
                'hasError' => false
            ],
            'phone' => [
                'value'    => '08043754356',
                'hasError' => false
            ],
            'tel-' => [
                'value'    => '044-380-3390',
                'hasError' => false
            ],
            'tel' => [
                'value'    => '0443803390',
                'hasError' => false
            ],
            'notzero' => [
                'value'    => '1443803390',
                'hasError' => true
            ],
        ];
    }
}
