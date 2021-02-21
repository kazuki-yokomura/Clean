<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Pattern;

/**
 * japanese tel pattern
 */
class JpTel extends Pattern
{
    /** @inheritdoc */
    protected $pattern = '/\A(((0(\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1}|[5789]0[-(]?\d{4})[-)]?)|\d{1,4}\-?)\d{4}|0120[-(]?\d{3}[-)]?\d{3})\z/';
}
