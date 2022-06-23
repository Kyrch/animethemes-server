<?php

declare(strict_types=1);

namespace App\Scout\Elasticsearch\Api\Field\Wiki\Anime\Theme\Entry;

use App\Models\Wiki\Anime\Theme\AnimeThemeEntry;
use App\Scout\Elasticsearch\Api\Field\BooleanField;

/**
 * Class EntryNsfwField.
 */
class EntryNsfwField extends BooleanField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(AnimeThemeEntry::ATTRIBUTE_NSFW);
    }
}