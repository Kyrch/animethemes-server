<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Auth\User\Me\List\Playlist;

use App\Enums\Auth\CrudPermission;
use App\Http\Api\Query\Query;
use App\Http\Controllers\Api\Auth\User\Me\List\MyLikeController;
use App\Http\Resources\List\Playlist\Collection\TrackCollection;
use App\Models\Auth\User;
use App\Models\List\Playlist;
use App\Models\List\Playlist\PlaylistTrack;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Class MyLikeIndexTest.
 */
class MyLikeIndexTest extends TestCase
{
    use WithFaker;

    /**
     * The My Like Index Endpoint shall be protected by sanctum.
     *
     * @return void
     */
    public function testProtected(): void
    {
        $response = $this->get(route('api.me.like.index'));

        $response->assertUnauthorized();
    }

    /**
     * The My Like Index Endpoint shall forbid users without the view playlist or view track permission.
     *
     * @return void
     */
    public function testForbiddenIfMissingPermission(): void
    {
        $user = User::factory()->createOne();

        Sanctum::actingAs($user);

        $response = $this->get(route('api.me.like.index'));

        $response->assertForbidden();
    }

    /**
     * The My Like Index Endpoint shall return likes owned by the user.
     *
     * @return void
     */
    public function testOnlySeesOwnedLikes(): void
    {
        $tracksCount = $this->faker->randomDigitNotNull();

        $playlist = Playlist::factory([Playlist::ATTRIBUTE_NAME => MyLikeController::PLAYLIST_NAME])
            ->for(User::factory())
            ->has(PlaylistTrack::factory()->count($tracksCount))
            ->create();

        $user = User::factory()->withPermissions(
            CrudPermission::VIEW->format(Playlist::class),
            CrudPermission::VIEW->format(PlaylistTrack::class),
        )->createOne();

        Sanctum::actingAs($user);

        $response = $this->get(route('api.me.like.index'));

        $response->assertJsonCount($tracksCount, TrackCollection::$wrap);

        $response->assertJson(
            json_decode(
                json_encode(
                    new TrackCollection($playlist->tracks, new Query())
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }
}
