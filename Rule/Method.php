<?php
// declare(strict_types=1);
namespace Clean\Rule;

use ReflectionObject;

/**
 * rules validate method class
 * useable static
 *
 * @used-by \Clean\Rule\Rules
 */
class Method
{
    /**
     * is_scalar
     *
     * @param  mixed  $value input value
     * @return bool
     */
    public static function isScalar($value): bool
    {
        return is_scalar($value);
    }

    /**
     * is_numeric
     * @param  mixed $value input value
     * @return bool
     */
    public static function isNumeric($value): bool
    {
        return is_numeric($value);
    }

    /**
     * min characters
     *
     * @param  string $str      input text
     * @param  array  $optional append value
     * @return bool
     */
    public static function minCharacters(string $str, array $optional): bool
    {
        extract($optional);
        $len = mb_strlen($str);

        return empty($min) || $len >= $min;
    }

    /**
     * max characters
     *
     * @param  string $str      input text
     * @param  array  $optional append value
     * @return bool
     */
    public static function maxCharacters(string $str, array $optional): bool
    {
        extract($optional);
        $len = mb_strlen($str);

        return empty($max) || $len <= $max;
    }

    /**
     * min value validate
     *
     * @param  int|float $numeric  input numeric
     * @param  array     $optional append value
     * @return bool
     */
    public static function minValue($numeric, array $optional): bool
    {
        extract($optional);

        return empty($min) || $numeric >= $min;
    }

    /**
     * max value validate
     *
     * @param  int|float $numeric  input numeric
     * @param  array     $optional append value
     * @return bool
     */
    public static function maxValue($numeric, array $optional): bool
    {
        extract($optional);

        return empty($max) || $numeric <= $max;
    }

    /**
     * pattern regex match
     *
     * @param  string $value    input value
     * @param  array  $optional append value
     * @return bool
     */
    public static function pregMatch(string $value, array $optional): bool
    {
        extract($optional);

        return preg_match($pattern, $value) === 1;
    }

    /**
     * can json encode
     *
     * @param  mixed $value    input value
     * @param  array $optional append value
     * @return bool
     */
    public static function canJsonEncode($value, array $optional): bool
    {
        extract($optional);
        $option = empty($option) ? 0 : $option;

        return json_encode($value, $option) !== false;
    }

    /**
     * object has constant
     *
     * @param  mixed $value    input value
     * @param  array $optional append value
     * @return bool
     */
    public static function hasConstant($value, array $optional): bool
    {
        extract($optional);

        $ref    = new ReflectionObject($object);
        $consts = $ref->getConstants();

        return in_array($value, $consts, true);
    }
}
