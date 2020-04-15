<?php
declare(strict_types=1);
namespace Clean\Value;

/**
 * integer value object
 */
class Integer extends Numeric
{
    /** @var int numeric precision */
    protected $precision = 0;
}
