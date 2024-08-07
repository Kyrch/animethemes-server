<?php

declare(strict_types=1);

namespace App\Events\Wiki\Image;

use App\Events\Base\Wiki\WikiUpdatedEvent;
use App\Models\Wiki\Image;

/**
 * Class ImageUpdated.
 *
 * @extends WikiUpdatedEvent<Image>
 */
class ImageUpdated extends WikiUpdatedEvent
{
    /**
     * Create a new event instance.
     *
     * @param  Image  $image
     */
    public function __construct(Image $image)
    {
        parent::__construct($image);
        $this->initializeEmbedFields($image);
    }

    /**
     * Get the model that has fired this event.
     *
     * @return Image
     */
    public function getModel(): Image
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
        return "Image '**{$this->getModel()->getName()}**' has been updated.";
    }
}
