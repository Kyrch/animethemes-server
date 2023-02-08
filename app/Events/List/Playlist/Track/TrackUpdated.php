<?php

declare(strict_types=1);

namespace App\Events\List\Playlist\Track;

use App\Events\Base\Admin\AdminUpdatedEvent;
use App\Models\List\Playlist;
use App\Models\List\Playlist\PlaylistTrack;

/**
 * Class TrackUpdated.
 *
 * @extends AdminUpdatedEvent<PlaylistTrack>
 */
class TrackUpdated extends AdminUpdatedEvent
{
    /**
     * The playlist the track belongs to.
     *
     * @var Playlist
     */
    protected Playlist $playlist;

    /**
     * Create a new event instance.
     *
     * @param  PlaylistTrack  $track
     */
    public function __construct(PlaylistTrack $track)
    {
        parent::__construct($track);
        $this->playlist = $track->playlist;
        $this->initializeEmbedFields($track);
    }

    /**
     * Get the model that has fired this event.
     *
     * @return PlaylistTrack
     */
    public function getModel(): PlaylistTrack
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
        return "Track '**{$this->getModel()->getName()}**' has been updated for Playlist '**{$this->playlist->getName()}**'.";
    }
}