<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Wiki\Video;

use App\GraphQL\Definition\Fields\StringField;
use App\Models\Wiki\Video;

/**
 * Class VideoLinkField.
 */
class VideoLinkField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Video::ATTRIBUTE_LINK, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The URL to stream the file from storage';
    }
}
