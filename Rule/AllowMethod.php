<?php
// declare(strict_types=1);
namespace Clean\Rule;

/**
 * allow rule method
 */
class AllowMethod
{
    /**
     * is empty
     *
     * @return bool
     */
    public static function isNull(array $structure, $option)
    {
        extract($option);

        return !isset($structure[$key]);
    }
}
