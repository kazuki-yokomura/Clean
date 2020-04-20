<?php
// declare(strict_types=1);
namespace Clean\Rule;

use Closure;

/**
 * roules object
 * 正直普通に アダプターにして Cake Laravel のバリデーション使えばよかったかも
 *
 * @uses \Clean\Rule\Method
 */
class Rules
{
    /** @const DEFAULT_RULE */
    const DEFAULT_RULE = [
        'allow'    => false,
        'final'    => false,
        'vars'     => [],
        'message'  => '',
        'value'    => null,
        'provider' => 'Clean\Rule\Method'
    ];

    private $rules = [];

    /**
     * add rule method
     *
     * @param string $name validate name
     * @param array  $rule rule
     */
    public function add(string $name, array $rule)
    {
        $rule += self::DEFAULT_RULE;
        $this->rules[$name] = $rule;

        return $this;
    }

    /**
     * apply rules
     *
     * @param  mixed  $value input value
     * @return array
     */
    public function apply($value): array
    {
        $errors = [];
        foreach ($this->rules as $name => $rule) {
            if ($this->validateMethod($value, $rule)) {
                if ($rule['allow']) {
                    break;
                }
                continue;
            }
            if ($rule['allow']) {
                continue;
            }

            $errors += $this->setErrors($name, $value, $rule);

            if ($rule['final']) {
                break;
            }
        }

        return $errors;
    }

    /**
     * apply method
     *
     * @param  mixed $value value
     * @param  array  $rule rule
     * @return bool
     */
    protected function validateMethod($value, array $rule): bool
    {
        return $rule['provider']::{$rule['method']}($value, $rule['vars']);
    }

    /**
     * set error message
     *
     * @param string $name  rule name
     * @param mixed  $value input value
     * @param array  $rule  rule
     */
    protected function setErrors(string $name, $value, array $rule)
    {
        $levels = array_reverse(explode('.', $name));

        $child = $this->getErrorMessage($value, $rule);
        foreach ($levels as $name) {
            $error = [];
            $error[$name] = $child;
            $child = $error;
        }

        return $error;
    }

    /**
     * get error message rule
     *
     * @return string
     */
    protected function getErrorMessage($value, array $rule): string
    {
        if (is_a($rule['message'], 'Closure')) {
            return $rule['message']($value);
        }

        return $rule['message'];
    }
}
