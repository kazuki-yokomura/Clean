<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Value\Integer;

/**
 *
 */
class IntegerTest extends TestCase
{
    /**
     * @dataProvider roundDataset
     */
    public function testRound($value, $option, $expected)
    {
        $integer = new class($value, $option) extends Integer {
            public function __construct($value, $option)
            {
                $this->roundType = $option['type'];
                $this->roundHalf = $option['round'];
                parent::__construct($value);
            }
        };

        $this->assertSame($expected, $integer->get());
    }

    public function roundDataset()
    {
        return [
            'integer_up' => [
                'value'     => 123.5,
                'option'    => [
                    'type'      => Integer::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 124
            ],
            'integer_down' => [
                'value'     => 123.5,
                'option'    => [
                    'type'      => Integer::ROUND_TYPE_DEFAULT,
                    'round'     => PHP_ROUND_HALF_DOWN,
                ],
                'expected'  => 123
            ],
            'round_up' => [
                'value'     => 123.4,
                'option'    => [
                    'type'      => Integer::ROUND_TYPE_UP,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 124
            ],
            'round_down' => [
                'value'     => 123.9,
                'option'    => [
                    'type'      => Integer::ROUND_TYPE_DOWN,
                    'round'     => PHP_ROUND_HALF_UP,
                ],
                'expected'  => 123
            ]
        ];
    }
}
