<?php

declare(strict_types=1);

namespace App\Concerns\GraphQL;

/**
 * Trait ResolvesDirectives.
 */
trait ResolvesDirectives
{
    /**
     * Convert the array of directives into a string format for GraphQL.
     *
     * @param  array<string, array>  $directives
     * @return string
     */
    public function resolveDirectives(array $directives): string
    {
        return collect($directives)
            ->map(function ($args, $directive) {
                if (filled($args)) {
                    $argsString = collect($args)
                        ->map(fn ($value, $key) => sprintf('%s: %s', $key, json_encode($value)))
                        ->implode(', ');

                    return sprintf('@%s(%s)', $directive, $argsString);
                }

                return "@{$directive}";
            })
            ->implode(' ');
    }
}
