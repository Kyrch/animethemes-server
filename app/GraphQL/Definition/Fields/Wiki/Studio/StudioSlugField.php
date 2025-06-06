<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Wiki\Studio;

use App\GraphQL\Definition\Fields\StringField;
use App\Models\Wiki\Studio;

/**
 * Class StudioSlugField.
 */
class StudioSlugField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Studio::ATTRIBUTE_SLUG, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The URL slug & route key of the resource';
    }
}
