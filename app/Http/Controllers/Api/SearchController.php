<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\Http\Api\InteractsWithSchema;
use App\Http\Api\Query\Query;
use App\Http\Api\Schema\Schema;
use App\Http\Api\Schema\SearchSchema;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SearchRequest;
use App\Http\Resources\SearchResource;
use Illuminate\Http\JsonResponse;

/**
 * Class SearchController.
 */
class SearchController extends Controller implements InteractsWithSchema
{
    /**
     * Search resource.
     *
     * @param  SearchRequest  $request
     * @return JsonResponse
     */
    public function show(SearchRequest $request): JsonResponse
    {
        $query = new Query($request->validated());

        $resource = new SearchResource($query);

        return $resource->toResponse($request);
    }

    /**
     * Get the underlying schema.
     *
     * @return Schema
     */
    public function schema(): Schema
    {
        return new SearchSchema();
    }
}