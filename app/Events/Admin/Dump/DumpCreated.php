<?php

declare(strict_types=1);

namespace App\Events\Admin\Dump;

use App\Events\Base\Admin\AdminCreatedEvent;
use App\Models\Admin\Dump;

/**
 * Class DumpCreated.
 *
 * @extends AdminCreatedEvent<Dump>
 */
class DumpCreated extends AdminCreatedEvent
{
    /**
     * Create a new event instance.
     *
     * @param  Dump  $dump
     */
    public function __construct(Dump $dump)
    {
        parent::__construct($dump);
    }

    /**
     * Get the model that has fired this event.
     *
     * @return Dump
     */
    public function getModel(): Dump
    {
        return $this->model;
    }

    /**
     * Get the description for the Discord message payload.
     *
     * @return string
     */
    protected function getDiscordMessageDescription(): string
    {
        return "Dump '**{$this->getModel()->getName()}**' has been created.";
    }
}
