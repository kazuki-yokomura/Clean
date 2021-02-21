<?php
declare(strict_types=1);
namespace ValueValidator\Value;

use ValueValidator\Value\Pattern;

/**
 * uuid value
 */
class Uuid extends Pattern
{
    /** @inheritdoc */
    protected $pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
}
