<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\List\Playlist;

use App\Actions\Http\Api\IndexAction;
use App\Http\Api\Query\Query;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\List\Playlist\BackwardIndexRequest;
use App\Http\Resources\List\Playlist\Collection\TrackCollection;
use App\Models\List\Playlist;
use App\Models\List\Playlist\BackwardPlaylistTrack;
use App\Models\List\Playlist\PlaylistTrack;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

/**
 * Class BackwardController.
 */
class BackwardController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(PlaylistTrack::class, 'track,playlist');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  BackwardIndexRequest  $request
     * @param  Playlist  $playlist
     * @param  IndexAction  $action
     * @return JsonResponse
     */
    public function index(BackwardIndexRequest $request, Playlist $playlist, IndexAction $action): JsonResponse
    {
        $query = new Query($request->validated());

        $constraint = function (Builder $query) use ($playlist) {
            $query->where(PlaylistTrack::ATTRIBUTE_ID, $playlist->last_id);
        };

        $builder = BackwardPlaylistTrack::query()->treeOf($constraint);

        $resources = $action->index($builder, $query, $request->schema());

        $collection = new TrackCollection($resources, $query);

        return $collection->toResponse($request);
    }
}