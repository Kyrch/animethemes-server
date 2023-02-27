<?php

declare(strict_types=1);

namespace Http\Api\Pivot\Wiki\StudioResource;

use App\Models\Auth\User;
use App\Models\Wiki\ExternalResource;
use App\Models\Wiki\Studio;
use App\Pivots\Wiki\StudioResource;
use Illuminate\Foundation\Testing\WithoutEvents;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Class StudioResourceUpdateTest.
 */
class StudioResourceUpdateTest extends TestCase
{
    use WithoutEvents;

    /**
     * The Studio Resource Update Destroy Endpoint shall be protected by sanctum.
     *
     * @return void
     */
    public function testProtected(): void
    {
        $studioResource = StudioResource::factory()
            ->for(Studio::factory())
            ->for(ExternalResource::factory(), StudioResource::RELATION_RESOURCE)
            ->createOne();

        $parameters = StudioResource::factory()->raw();

        $response = $this->put(route('api.studioresource.update', ['studio' => $studioResource->studio, 'resource' => $studioResource->resource] + $parameters));

        $response->assertUnauthorized();
    }

    /**
     * The Studio Resource Update Endpoint shall forbid users without the update studio & update resource permissions.
     *
     * @return void
     */
    public function testForbidden(): void
    {
        $studioResource = StudioResource::factory()
            ->for(Studio::factory())
            ->for(ExternalResource::factory(), StudioResource::RELATION_RESOURCE)
            ->createOne();

        $parameters = StudioResource::factory()->raw();

        $user = User::factory()->createOne();

        Sanctum::actingAs($user);

        $response = $this->put(route('api.studioresource.update', ['studio' => $studioResource->studio, 'resource' => $studioResource->resource] + $parameters));

        $response->assertForbidden();
    }

    /**
     * The Studio Resource Update Endpoint shall update a studio resource.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $studioResource = StudioResource::factory()
            ->for(Studio::factory())
            ->for(ExternalResource::factory(), StudioResource::RELATION_RESOURCE)
            ->createOne();

        $parameters = StudioResource::factory()->raw();

        $user = User::factory()->withPermissions(['update studio', 'update external resource'])->createOne();

        Sanctum::actingAs($user);

        $response = $this->put(route('api.studioresource.update', ['studio' => $studioResource->studio, 'resource' => $studioResource->resource] + $parameters));

        $response->assertOk();
    }
}