<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Admin\Dump;

use App\Models\Admin\Dump;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\WithoutEvents;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Class DumpRestoreTest.
 */
class DumpRestoreTest extends TestCase
{
    use WithoutEvents;

    /**
     * The Dump Restore Endpoint shall be protected by sanctum.
     *
     * @return void
     */
    public function testProtected(): void
    {
        $dump = Dump::factory()->createOne();

        $dump->delete();

        $response = $this->patch(route('api.dump.restore', ['dump' => $dump]));

        $response->assertUnauthorized();
    }

    /**
     * The Dump Restore Endpoint shall forbid users without the restore dump permission.
     *
     * @return void
     */
    public function testForbidden(): void
    {
        $dump = Dump::factory()->createOne();

        $dump->delete();

        $user = User::factory()->createOne();

        Sanctum::actingAs($user);

        $response = $this->patch(route('api.dump.restore', ['dump' => $dump]));

        $response->assertForbidden();
    }

    /**
     * The Dump Restore Endpoint shall forbid users from restoring a dump that isn't trashed.
     *
     * @return void
     */
    public function testTrashed(): void
    {
        $dump = Dump::factory()->createOne();

        $user = User::factory()->withPermission('restore dump')->createOne();

        Sanctum::actingAs($user);

        $response = $this->patch(route('api.dump.restore', ['dump' => $dump]));

        $response->assertForbidden();
    }

    /**
     * The Dump Restore Endpoint shall restore the dump.
     *
     * @return void
     */
    public function testRestored(): void
    {
        $dump = Dump::factory()->createOne();

        $dump->delete();

        $user = User::factory()->withPermission('restore dump')->createOne();

        Sanctum::actingAs($user);

        $response = $this->patch(route('api.dump.restore', ['dump' => $dump]));

        $response->assertOk();
        static::assertNotSoftDeleted($dump);
    }
}