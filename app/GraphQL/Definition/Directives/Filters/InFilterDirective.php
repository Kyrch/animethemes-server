<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Directives\Filters;

use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;

/**
 * Class InFilterDirective.
 */
class InFilterDirective extends FilterDirective
{
    /**
     * Create the argument for the directive.
     *
     * @return string
     */
    public function toString(): string
    {
        return Str::of($this->field->getName().'_in')
            ->append(': ')
            ->append(Type::listOf($this->type)->toString())
            ->append(" @in(key: \"{$this->field->getColumn()}\")")
            ->toString();
    }
}
