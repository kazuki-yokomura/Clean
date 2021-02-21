<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Text;

/**
 * regex pattern value object
 */
class Pattern extends Text
{
    /** @var string regex pattern */
    protected $pattern;

    /**
     * set rule
     */
    protected function setRule(): void
    {
        parent::setRule();
        $this->rules
            ->add('missPattern', [
                'method'  => 'pregMatch',
                'vars'    => ['pattern' => $this->pattern],
                'message' => 'Miss match pattern.'
            ]);
    }
}
