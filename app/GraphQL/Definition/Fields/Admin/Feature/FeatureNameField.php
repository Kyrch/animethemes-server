<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Admin\Feature;

use App\GraphQL\Definition\Fields\StringField;
use App\Models\Admin\Feature;

/**
 * Class FeatureNameField.
 */
class FeatureNameField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Feature::ATTRIBUTE_NAME, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The title of the resource';
    }
}
