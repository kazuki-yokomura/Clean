<?php
declare(strict_types=1, encoding='UTF-8');
namespace Clean\Rule;

use Closure;

/**
 * roules object
 *
 * @uses \Clean\Rule\Method
 */
class Rules
{
    /** @const DEFAULT_RULE */
    const DEFAULT_RULE = [
        'final' => false,
        'vars'  => []
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
            if (Method::$method($value, $rule['vars'])) {
                continue;
            }
            $errors[] = $name;
            if ($rule['final']) {
                break;
            }
        }

        return $errors;
    }
}
