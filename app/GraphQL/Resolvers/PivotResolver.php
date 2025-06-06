<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Class PivotResolver.
 */
class PivotResolver
{
    /**
     * Resolve the pivot field.
     *
     * @param  array  $root
     * @param  array  $args
     * @param  GraphQLContext  $context
     * @param  ResolveInfo  $resolveInfo
     * @return mixed
     */
    public function resolve(array $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): mixed
    {
        /** @var Model $model */
        $model = Arr::get($root, 'node');

        $pivot = current($model->getRelations());

        return $pivot->{Str::snake($resolveInfo->fieldName)};
    }
}
