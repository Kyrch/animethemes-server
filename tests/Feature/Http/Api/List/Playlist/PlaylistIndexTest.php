<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\List\Playlist;

use App\Contracts\Http\Api\Field\SortableField;
use App\Enums\Http\Api\Filter\TrashedStatus;
use App\Enums\Http\Api\Sort\Direction;
use App\Enums\Models\List\PlaylistVisibility;
use App\Enums\Models\Wiki\ImageFacet;
use App\Http\Api\Criteria\Filter\TrashedCriteria;
use App\Http\Api\Criteria\Paging\Criteria;
use App\Http\Api\Criteria\Paging\OffsetCriteria;
use App\Http\Api\Field\Field;
use App\Http\Api\Include\AllowedInclude;
use App\Http\Api\Parser\FieldParser;
use App\Http\Api\Parser\FilterParser;
use App\Http\Api\Parser\IncludeParser;
use App\Http\Api\Parser\PagingParser;
use App\Http\Api\Parser\SortParser;
use App\Http\Api\Query\List\Playlist\PlaylistReadQuery;
use App\Http\Api\Schema\List\PlaylistSchema;
use App\Http\Resources\List\Collection\PlaylistCollection;
use App\Http\Resources\List\Resource\PlaylistResource;
use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\List\Playlist;
use App\Models\List\Playlist\PlaylistTrack;
use App\Models\Wiki\Image;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Class PlaylistIndexTest.
 */
class PlaylistIndexTest extends TestCase
{
    use WithFaker;
    use WithoutEvents;

    /**
     * By default, the Playlist Index Endpoint shall return a collection of Playlist Resources with public visibility.
     *
     * @return void
     */
    public function testDefault(): void
    {
        $publicCount = $this->faker->randomDigitNotNull();

        $playlists = Playlist::factory()
            ->count($publicCount)
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $unlistedCount = $this->faker->randomDigitNotNull();

        Playlist::factory()
            ->count($unlistedCount)
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::UNLISTED]);

        $privateCount = $this->faker->randomDigitNotNull();

        Playlist::factory()
            ->count($privateCount)
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PRIVATE]);

        $response = $this->get(route('api.playlist.index'));

        $response->assertJsonCount($publicCount, PlaylistCollection::$wrap);

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery()))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall be paginated.
     *
     * @return void
     */
    public function testPaginated(): void
    {
        Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $response = $this->get(route('api.playlist.index'));

        $response->assertJsonStructure([
            PlaylistCollection::$wrap,
            'links',
            'meta',
        ]);
    }

    /**
     * The Playlist Index Endpoint shall allow inclusion of related resources.
     *
     * @return void
     */
    public function testAllowedIncludePaths(): void
    {
        $schema = new PlaylistSchema();

        $allowedIncludes = collect($schema->allowedIncludes());

        $selectedIncludes = $allowedIncludes->random($this->faker->numberBetween(1, $allowedIncludes->count()));

        $includedPaths = $selectedIncludes->map(fn (AllowedInclude $include) => $include->path());

        $parameters = [
            IncludeParser::param() => $includedPaths->join(','),
        ];

        Playlist::factory()
            ->for(User::factory())
            ->has(PlaylistTrack::factory(), Playlist::RELATION_FIRST)
            ->has(PlaylistTrack::factory(), Playlist::RELATION_LAST)
            ->has(PlaylistTrack::factory()->count($this->faker->randomDigitNotNull()), Playlist::RELATION_TRACKS)
            ->has(Image::factory()->count($this->faker->randomDigitNotNull()))
            ->count($this->faker->randomDigitNotNull())
            ->create([
                Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC,
            ]);

        $playlists = Playlist::with($includedPaths->all())->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall implement sparse fieldsets.
     *
     * @return void
     */
    public function testSparseFieldsets(): void
    {
        $schema = new PlaylistSchema();

        $fields = collect($schema->fields());

        $includedFields = $fields->random($this->faker->numberBetween(1, $fields->count()));

        $parameters = [
            FieldParser::param() => [
                PlaylistResource::$wrap => $includedFields->map(fn (Field $field) => $field->getKey())->join(','),
            ],
        ];

        $playlists = Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support sorting resources.
     *
     * @return void
     */
    public function testSorts(): void
    {
        $schema = new PlaylistSchema();

        $sort = collect($schema->fields())
            ->filter(fn (Field $field) => $field instanceof SortableField)
            ->map(fn (SortableField $field) => $field->getSort())
            ->random();

        $parameters = [
            SortParser::param() => $sort->format(Direction::getRandomInstance()),
        ];

        $query = new PlaylistReadQuery($parameters);

        Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    $query->collection($query->index())
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by created_at.
     *
     * @return void
     */
    public function testCreatedAtFilter(): void
    {
        $createdFilter = $this->faker->date();
        $excludedDate = $this->faker->date();

        $parameters = [
            FilterParser::param() => [
                BaseModel::ATTRIBUTE_CREATED_AT => $createdFilter,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Carbon::withTestNow($createdFilter, function () {
            Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);
        });

        Carbon::withTestNow($excludedDate, function () {
            Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);
        });

        $playlists = Playlist::query()->where(BaseModel::ATTRIBUTE_CREATED_AT, $createdFilter)->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by updated_at.
     *
     * @return void
     */
    public function testUpdatedAtFilter(): void
    {
        $updatedFilter = $this->faker->date();
        $excludedDate = $this->faker->date();

        $parameters = [
            FilterParser::param() => [
                BaseModel::ATTRIBUTE_UPDATED_AT => $updatedFilter,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Carbon::withTestNow($updatedFilter, function () {
            Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);
        });

        Carbon::withTestNow($excludedDate, function () {
            Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);
        });

        $playlists = Playlist::query()->where(BaseModel::ATTRIBUTE_UPDATED_AT, $updatedFilter)->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by trashed.
     *
     * @return void
     */
    public function testWithoutTrashedFilter(): void
    {
        $parameters = [
            FilterParser::param() => [
                TrashedCriteria::PARAM_VALUE => TrashedStatus::WITHOUT,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist = Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist->each(function (Playlist $playlist) {
            $playlist->delete();
        });

        $playlists = Playlist::withoutTrashed()->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by trashed.
     *
     * @return void
     */
    public function testWithTrashedFilter(): void
    {
        $parameters = [
            FilterParser::param() => [
                TrashedCriteria::PARAM_VALUE => TrashedStatus::WITH,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist = Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist->each(function (Playlist $playlist) {
            $playlist->delete();
        });

        $playlists = Playlist::withTrashed()->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by trashed.
     *
     * @return void
     */
    public function testOnlyTrashedFilter(): void
    {
        $parameters = [
            FilterParser::param() => [
                TrashedCriteria::PARAM_VALUE => TrashedStatus::ONLY,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist = Playlist::factory()
            ->count($this->faker->randomDigitNotNull())
            ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

        $deletePlaylist->each(function (Playlist $playlist) {
            $playlist->delete();
        });

        $playlists = Playlist::onlyTrashed()->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support filtering by deleted_at.
     *
     * @return void
     */
    public function testDeletedAtFilter(): void
    {
        $deletedFilter = $this->faker->date();
        $excludedDate = $this->faker->date();

        $parameters = [
            FilterParser::param() => [
                BaseModel::ATTRIBUTE_DELETED_AT => $deletedFilter,
                TrashedCriteria::PARAM_VALUE => TrashedStatus::WITH,
            ],
            PagingParser::param() => [
                OffsetCriteria::SIZE_PARAM => Criteria::MAX_RESULTS,
            ],
        ];

        Carbon::withTestNow($deletedFilter, function () {
            $playlists = Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

            $playlists->each(function (Playlist $item) {
                $item->delete();
            });
        });

        Carbon::withTestNow($excludedDate, function () {
            $playlists = Playlist::factory()
                ->count($this->faker->randomDigitNotNull())
                ->create([Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC]);

            $playlists->each(function (Playlist $item) {
                $item->delete();
            });
        });

        $playlists = Playlist::withTrashed()->where(BaseModel::ATTRIBUTE_DELETED_AT, $deletedFilter)->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }

    /**
     * The Playlist Index Endpoint shall support constrained eager loading of images by facet.
     *
     * @return void
     */
    public function testImagesByFacet(): void
    {
        $facetFilter = ImageFacet::getRandomInstance();

        $parameters = [
            FilterParser::param() => [
                Image::ATTRIBUTE_FACET => $facetFilter->description,
            ],
            IncludeParser::param() => Playlist::RELATION_IMAGES,
        ];

        Playlist::factory()
            ->has(Image::factory()->count($this->faker->randomDigitNotNull()))
            ->count($this->faker->randomDigitNotNull())
            ->create([
                Playlist::ATTRIBUTE_VISIBILITY => PlaylistVisibility::PUBLIC,
            ]);

        $playlists = Playlist::with([
            Playlist::RELATION_IMAGES => function (BelongsToMany $query) use ($facetFilter) {
                $query->where(Image::ATTRIBUTE_FACET, $facetFilter->value);
            },
        ])
        ->get();

        $response = $this->get(route('api.playlist.index', $parameters));

        $response->assertJson(
            json_decode(
                json_encode(
                    (new PlaylistCollection($playlists, new PlaylistReadQuery($parameters)))
                        ->response()
                        ->getData()
                ),
                true
            )
        );
    }
}