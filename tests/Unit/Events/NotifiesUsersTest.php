<?php

declare(strict_types=1);

namespace Tests\Unit\Events;

use App\Contracts\Events\NotifiesUsersEvent;
use App\Listeners\NotifiesUsers;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Class NotifiesUsersTest.
 */
class NotifiesUsersTest extends TestCase
{
    /**
     * NotifiesUsers shall listen to NotifiesUsersEvent.
     *
     * @return void
     */
    public function testListening(): void
    {
        Event::assertListening(NotifiesUsersEvent::class, NotifiesUsers::class);
    }
}
