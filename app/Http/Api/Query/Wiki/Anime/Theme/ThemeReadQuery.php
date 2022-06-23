<?php

declare(strict_types=1);

namespace App\Http\Api\Query\Wiki\Anime\Theme;

use App\Http\Api\Query\Base\EloquentReadQuery;
use App\Http\Api\Schema\EloquentSchema;
use App\Http\Api\Schema\Wiki\Anime\ThemeSchema;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Wiki\Anime\Collection\ThemeCollection;
use App\Http\Resources\Wiki\Anime\Resource\ThemeResource;
use App\Models\Wiki\Anime\AnimeTheme;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ThemeReadQuery.
 */
class ThemeReadQuery extends EloquentReadQuery
{
    /**
     * Get the resource schema.
     *
     * @return EloquentSchema
     */
    public function schema(): EloquentSchema
    {
        return new ThemeSchema();
    }

    /**
     * Get the query builder of the resource.
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return AnimeTheme::query();
    }

    /**
     * Get the json resource.
     *
     * @param  mixed  $resource
     * @return BaseResource
     */
    public function resource(mixed $resource): BaseResource
    {
        return new ThemeResource($resource, $this);
    }

    /**
     * Get the resource collection.
     *
     * @param  mixed  $resource
     * @return BaseCollection
     */
    public function collection(mixed $resource): BaseCollection
    {
        return new ThemeCollection($resource, $this);
    }
}