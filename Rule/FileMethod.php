<?php
// declare(strict_types=1);
namespace ValueValidator\Rule;

/**
 * rules validate method class
 * useable static
 *
 * @used-by \ValueValidator\Rule\Rules
 */
class FileMethod
{
    /**
     * is file
     *
     * @param  mixed  $file upload value
     * @return bool
     */
    public function isFile($file)
    {
        if (!is_array($file)) {
            return false;
        }

        $info = array_flip([
            'name',
            'type',
            'size',
            'tmp_name',
            'error'
        ]);

        $extra = array_diff_key($info, $file);
        $few   = array_diff_key($file, $info);

        return !$extra && !$few;
    }

    /**
     * has upload error
     *
     * @param  array   $file finfo
     * @return bool
     */
    public function hasUploadError(array $file)
    {
        return $file['error'] === 0;
    }

    /**
     * max name length
     *
     * @param  array  $file     finfo
     * @param  array  $optional add option
     * @return bool
     */
    public function maxNameLength(array $file, array $optional)
    {
        extract($optional);
        $len = mb_strlen($file['name']);

        return empty($maxCharacters) || $len <= $maxCharacters;
    }

    /**
     * max file size
     *
     * @param  array  $file     finfo
     * @param  array  $optional add option
     * @return bool
     */
    public function maxBytes(array $file, array $optional)
    {
        extract($optional);

        return empty($maxBytes) || $file['size'] <= $maxBytes;
    }

    /**
     * allow extension
     *
     * @param  array  $file     finfo
     * @param  array  $optional add option
     * @return bool
     */
    public function allowExtensions(array $file, array $optional)
    {
        extract($optional);
        if (empty($allowExtensions)) {
            return true;
        }
        if (strpos($file['name'], '.') === false) {
            return false;
        }

        $name      = explode('.', $file['name']);
        $extension = array_pop($name);

        return in_array($extension, $allowExtensions);
    }

    /**
     * allow mime type
     *
     * @param  array  $file     finfo
     * @param  array  $optional add option
     * @return bool
     */
    public function allowMimeTypes(array $file, array $optional)
    {
        extract($optional);

        return in_array($file['type'], $allowMimeTypes);
    }
}
