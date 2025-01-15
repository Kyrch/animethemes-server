<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\User\Me\List;

use App\Actions\Http\Api\IndexAction;
use App\Filament\Resources\List\Playlist\Track;
use App\Http\Api\Query\Query;
use App\Http\Api\Schema\List\Playlist\TrackSchema;
use App\Http\Api\Schema\Schema;
use App\Http\Controllers\Api\BaseController;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Resources\List\Playlist\Collection\TrackCollection;
use App\Models\Auth\User;
use App\Models\List\Playlist;
use Illuminate\Support\Facades\Auth;

/**
 * Class MyLikeController.
 */
class MyLikeController extends BaseController
{
    final public const PLAYLIST_NAME = 'Liked Songs';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(Authenticate::using('sanctum'));
        parent::__construct(Track::class, 'track');
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

        /** @var User $user */
        $user = Auth::user();

        $builder = $user->playlists()->getQuery()->firstWhere(Playlist::ATTRIBUTE_NAME, static::PLAYLIST_NAME)->tracks()->getQuery();

        $tracks = $action->index($builder, $query, $request->schema());

        return new TrackCollection($tracks, $query);
    }

    /**
     * Get the underlying schema.
     *
     * @return Schema
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function schema(): Schema
    {
        return new TrackSchema();
    }
}
