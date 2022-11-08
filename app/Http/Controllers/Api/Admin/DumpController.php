<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Dump\DumpDestroyRequest;
use App\Http\Requests\Api\Admin\Dump\DumpForceDeleteRequest;
use App\Http\Requests\Api\Admin\Dump\DumpIndexRequest;
use App\Http\Requests\Api\Admin\Dump\DumpRestoreRequest;
use App\Http\Requests\Api\Admin\Dump\DumpShowRequest;
use App\Http\Requests\Api\Admin\Dump\DumpStoreRequest;
use App\Http\Requests\Api\Admin\Dump\DumpUpdateRequest;
use App\Models\Admin\Dump;
use Illuminate\Http\JsonResponse;

/**
 * Class DumpController.
 */
class DumpController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(Dump::class, 'dump');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  DumpIndexRequest  $request
     * @return JsonResponse
     */
    public function index(DumpIndexRequest $request): JsonResponse
    {
        $dumps = $request->getQuery()->index();

        return $dumps->toResponse($request);
    }

    /**
     * Store a newly created resource.
     *
     * @param  DumpStoreRequest  $request
     * @return JsonResponse
     */
    public function store(DumpStoreRequest $request): JsonResponse
    {
        $resource = $request->getQuery()->store();

        return $resource->toResponse($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  DumpShowRequest  $request
     * @param  Dump  $dump
     * @return JsonResponse
     */
    public function show(DumpShowRequest $request, Dump $dump): JsonResponse
    {
        $resource = $request->getQuery()->show($dump);

        return $resource->toResponse($request);
    }

    /**
     * Update the specified resource.
     *
     * @param  DumpUpdateRequest  $request
     * @param  Dump  $dump
     * @return JsonResponse
     */
    public function update(DumpUpdateRequest $request, Dump $dump): JsonResponse
    {
        $resource = $request->getQuery()->update($dump);

        return $resource->toResponse($request);
    }

    /**
     * Remove the specified resource.
     *
     * @param  DumpDestroyRequest  $request
     * @param  Dump  $dump
     * @return JsonResponse
     */
    public function destroy(DumpDestroyRequest $request, Dump $dump): JsonResponse
    {
        $resource = $request->getQuery()->destroy($dump);

        return $resource->toResponse($request);
    }

    /**
     * Restore the specified resource.
     *
     * @param  DumpRestoreRequest  $request
     * @param  Dump  $dump
     * @return JsonResponse
     */
    public function restore(DumpRestoreRequest $request, Dump $dump): JsonResponse
    {
        $resource = $request->getQuery()->restore($dump);

        return $resource->toResponse($request);
    }

    /**
     * Hard-delete the specified resource.
     *
     * @param  DumpForceDeleteRequest  $request
     * @param  Dump  $dump
     * @return JsonResponse
     */
    public function forceDelete(DumpForceDeleteRequest $request, Dump $dump): JsonResponse
    {
        return $request->getQuery()->forceDelete($dump);
    }
}