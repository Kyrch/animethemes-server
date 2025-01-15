<?php

declare(strict_types=1);

namespace App\Http\Controller\Api\List\Playlist;

use App\Actions\Http\Api\IndexAction;
use App\Actions\Http\Api\List\Playlist\Track\DestroyTrackAction;
use App\Actions\Http\Api\List\Playlist\Track\ForceDeleteTrackAction;
use App\Actions\Http\Api\List\Playlist\Track\RestoreTrackAction;
use App\Actions\Http\Api\List\Playlist\Track\StoreTrackAction;
use App\Actions\Http\Api\ShowAction;
use App\Enums\Models\List\PlaylistVisibility;
use App\Features\AllowPlaylistManagement;
use App\Http\Api\Query\Query;
use App\Http\Api\Schema\List\Playlist\TrackSchema;
use App\Http\Api\Schema\Schema;
use App\Http\Controllers\Api\Auth\User\Me\List\MyLikeController;
use App\Http\Controllers\Api\BaseController;
use App\Http\Middleware\Models\List\UserExceedsPlaylistLimit;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\ShowRequest;
use App\Http\Requests\Api\StoreRequest;
use App\Http\Resources\List\Playlist\Collection\TrackCollection;
use App\Http\Resources\List\Playlist\Resource\TrackResource;
use App\Models\List\Playlist;
use App\Models\List\Playlist\PlaylistTrack;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Illuminate\Support\Str;

/**
 * Class LikeController.
 */
class LikeController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(Playlist::class, 'track');

        $isPlaylistManagementAllowed = Str::of(EnsureFeaturesAreActive::class)
            ->append(':')
            ->append(AllowPlaylistManagement::class)
            ->__toString();

        $this->middleware($isPlaylistManagementAllowed)->except(['index', 'show']);
        $this->middleware(UserExceedsPlaylistLimit::class)->only(['store', 'restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  IndexRequest  $request
     * @param  IndexAction  $action
     * @return TrackCollection
     */
    public function index(IndexRequest $request, IndexAction $action): TrackCollection
    {
        $query = new Query($request->validated());

        $builder = PlaylistTrack::query()
            ->whereRelation(PlaylistTrack::RELATION_PLAYLIST, Playlist::ATTRIBUTE_NAME, MyLikeController::PLAYLIST_NAME)
            ->whereRelation(PlaylistTrack::RELATION_PLAYLIST, Playlist::ATTRIBUTE_VISIBILITY, PlaylistVisibility::PUBLIC->value);

        $resources = $action->index($builder, $query, $request->schema());

        return new TrackCollection($resources, $query);
    }

    /**
     * Store a newly created resource.
     *
     * @param  StoreRequest  $request
     * @param  StoreTrackAction  $action
     * @return TrackResource
     *
     * @throws Exception
     */
    public function store(StoreRequest $request, StoreTrackAction $action): TrackResource
    {
        $playlist = Playlist::query()->firstOrCreate([
            Playlist::ATTRIBUTE_NAME => MyLikeController::PLAYLIST_NAME,
            Playlist::ATTRIBUTE_USER => Auth::id(),
        ], [
            Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PRIVATE->value
        ]);

        $track = $action->store($playlist, PlaylistTrack::query(), $request->validated());

        return new TrackResource($track, new Query());
    }

    /**
     * Display the specified resource.
     *
     * @param  ShowRequest  $request
     * @param  PlaylistTrack  $track
     * @param  ShowAction  $action
     * @return TrackResource
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function show(ShowRequest $request, PlaylistTrack $track, ShowAction $action): TrackResource
    {
        $query = new Query($request->validated());

        $show = $action->show($track, $query, $request->schema());

        return new TrackResource($show, $query);
    }

    /**
     * Remove the specified resource.
     *
     * @param  PlaylistTrack  $track
     * @param  DestroyTrackAction  $action
     * @return TrackResource
     *
     * @throws Exception
     */
    public function destroy(PlaylistTrack $track, DestroyTrackAction $action): TrackResource
    {
        $playlist = Playlist::query()
            ->where(Playlist::ATTRIBUTE_NAME, MyLikeController::PLAYLIST_NAME)
            ->where(Playlist::ATTRIBUTE_USER, Auth::id())
            ->firstOrFail();

        $deleted = $action->destroy($playlist, $track);

        return new TrackResource($deleted, new Query());
    }

    /**
     * Restore the specified resource.
     *
     * @param  PlaylistTrack  $track
     * @param  RestoreTrackAction  $action
     * @return TrackResource
     *
     * @throws Exception
     */
    public function restore(PlaylistTrack $track, RestoreTrackAction $action): TrackResource
    {
        $playlist = Playlist::query()
            ->where(Playlist::ATTRIBUTE_NAME, MyLikeController::PLAYLIST_NAME)
            ->where(Playlist::ATTRIBUTE_USER, Auth::id())
            ->firstOrFail();

        $restored = $action->restore($playlist, $track);

        return new TrackResource($restored, new Query());
    }

    /**
     * Hard-delete the specified resource.
     *
     * @param  PlaylistTrack  $track
     * @param  ForceDeleteTrackAction  $action
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function forceDelete(PlaylistTrack $track, ForceDeleteTrackAction $action): JsonResponse
    {
        $playlist = Playlist::query()
            ->where(Playlist::ATTRIBUTE_NAME, MyLikeController::PLAYLIST_NAME)
            ->where(Playlist::ATTRIBUTE_USER, Auth::id())
            ->firstOrFail();

        $message = $action->forceDelete($playlist, $track);

        return new JsonResponse([
            'message' => $message,
        ]);
    }

    /**
     * Get the underlying schema.
     *
     * @return Schema
     */
    public function schema(): Schema
    {
        return new TrackSchema();
    }
}
