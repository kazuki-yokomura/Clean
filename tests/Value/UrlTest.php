<?php

use PHPUnit\Framework\TestCase;
use ValueValidator\Value\Url;

/**
 * pattern test
 */
class UrlTest extends TestCase
{
    /**
     * @dataProvider urlDataset
     */
    public function testUrl($value, $hasError)
    {
        $pattern = new Url($value);

        $this->assertSame($hasError, $pattern->hasErrors());
    }

    public function urlDataset()
    {
        return [
            'phpnet' => [
                'value'    => 'https://www.php.net/',
                'hasError' => false
            ],
            'anchor' => [
                'value'    => 'https://www.php.net/manual/ja/intro-whatis.php#intro-whatis',
                'hasError' => false
            ],
            'anchor' => [
                'value'    => 'https://www.google.com/search?client=firefox-b-d&sxsrf=ALeKk01CkqWSoBzHNuGcssuRULNm5832ig%3A1587194777923&ei=mauaXqbsN4rmwQOBqZ-IAQ&q=php&oq=php&gs_lcp=CgZwc3ktYWIQAzIECCMQJzIECCMQJzIECCMQJzICCAAyBAgAEEMyAggAMgQIABBDMgQIABBDOgYIIxAnEBM6CAgAEAUQHhATOggIABAIEB4QEzoECAAQBDoHCAAQgwEQBDoFCAAQgwFQtgpYjQxgnA5oAXAAeACAAYYBiAHuA5IBAzAuNJgBAKABAaoBB2d3cy13aXo&sclient=psy-ab&ved=0ahUKEwjmnLWnufHoAhUKc3AKHYHUBxEQ4dUDCAs&uact=5',
                'hasError' => false
            ],
            'notUrl' => [
                'value'    => 'httpie',
                'hasError' => true
            ],
        ];
    }
}
