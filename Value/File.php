<?php
declare(strict_types=1);
namespace ValueValidator\Value;

use ValueValidator\Value\Foundation;

/**
 * file value object
 */
class File extends Foundation
{
    /** @var int $maxBytes max file bytes */
    protected $maxBytes = 0;

    /** @var int $maxNameLength max file name characters */
    protected $maxNameLength = 0;

    /** @var array $allowExtensions allow extensions */
    protected $allowExtensions = [];

    /** @var array $allowMimeTypes allow mime types */
    protected $allowMimeTypes = [];

    /** @var array $uploadErrors file upload error */
    protected $uploadErrors = [
        UPLOAD_ERR_OK => 'There is no error, the file uploaded with success',
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];

    /**
     * constractor
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);

        if ($this->validate($value)) {
            $this->value = $value;
        }
    }

    /** @inheritdoc */
    public function get(): array
    {
        return $this->value;
    }

    /**
     * parse string this value
     *
     * @return string
     */
    public function __toString()
    {
        return (string)json_encode($this->value);
    }

    /** @inheritdoc */
    protected function setRule(): void
    {
        $this->rules
            ->add('isFile', [
                'final' => true,
                'method' => 'isFile',
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => 'Is not upload file.'
            ])
            ->add('uploadError', [
                'final' => true,
                'method' => 'hasUploadError',
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => function (array $value) {
                    if (isset($this->uploadErrors[$value['error']])) {
                        return $this->uploadErrors[$value['error']];
                    }

                    return "Has error file [{$value['error']}].";
                }
            ])
            ->add('maxNameLength', [
                'method' => 'maxNameLength',
                'vars' => ['maxCharacters' => $this->maxNameLength],
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => function (array $value) {
                    $format = "Can't use %s characters name. Can use short to %s characters";

                    return sprintf($format, mb_strlen($value['name']), $this->maxNameLength);
                }
            ])
            ->add('maxBytes', [
                'method' => 'maxBytes',
                'vars' => ['maxBytes' => $this->maxBytes],
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => function (array $value) {
                    $format = 'Maximam size is %s. Can\'t input %s.';

                    return sprintf($format, number_format($this->maxBytes), number_format($value['size']));
                }
            ])
            ->add('allowExtensions', [
                'method' => 'allowExtensions',
                'vars' => ['allowExtensions' => $this->allowExtensions],
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => function (array $value) {
                    return "{$value['name']} has invalid extensions.";
                }
            ])
            ->add('allowMimeTypes', [
                'method' => 'allowMimeTypes',
                'vars' => ['allowMimeTypes' => $this->allowMimeTypes],
                'provider' => 'ValueValidator\Rule\FileMethod',
                'message' => function (array $value) {
                    return "{$value['type']} is invalid type.";
                }
            ]);
    }
}
