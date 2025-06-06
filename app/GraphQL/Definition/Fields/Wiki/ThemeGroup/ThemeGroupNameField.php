<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Wiki\ThemeGroup;

use App\GraphQL\Definition\Fields\StringField;
use App\Models\Wiki\Group;

/**
 * Class ThemeGroupNameField.
 */
class ThemeGroupNameField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Group::ATTRIBUTE_NAME, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The name of the group';
    }
}
