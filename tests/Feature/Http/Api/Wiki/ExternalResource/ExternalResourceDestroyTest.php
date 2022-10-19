<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Wiki\ExternalResource;

use App\Models\Auth\User;
use App\Models\Wiki\ExternalResource;
use Illuminate\Foundation\Testing\WithoutEvents;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Class ExternalResourceDestroyTest.
 */
class ExternalResourceDestroyTest extends TestCase
{
    use WithoutEvents;

    /**
     * The External Resource Destroy Endpoint shall be protected by sanctum.
     *
     * @return void
     */
    public function testProtected(): void
    {
        $resource = ExternalResource::factory()->createOne();

        $response = $this->delete(route('api.resource.destroy', ['resource' => $resource]));

        $response->assertUnauthorized();
    }

    /**
     * The External Resource Destroy Endpoint shall forbid users without the delete external resource permission.
     *
     * @return void
     */
    public function testForbidden(): void
    {
        $resource = ExternalResource::factory()->createOne();

        $user = User::factory()->createOne();

        Sanctum::actingAs($user);

        $response = $this->delete(route('api.resource.destroy', ['resource' => $resource]));

        $response->assertForbidden();
    }

    /**
     * The External Resource Destroy Endpoint shall delete the resource.
     *
     * @return void
     */
    public function testDeleted(): void
    {
        $resource = ExternalResource::factory()->createOne();

        $user = User::factory()->withPermission('delete external resource')->createOne();

        Sanctum::actingAs($user);

        $response = $this->delete(route('api.resource.destroy', ['resource' => $resource]));

        $response->assertOk();
        static::assertSoftDeleted($resource);
    }
}
