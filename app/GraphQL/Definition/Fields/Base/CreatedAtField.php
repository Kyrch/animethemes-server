<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Base;

use App\GraphQL\Definition\Fields\DateTimeTzField;
use App\Models\BaseModel;

/**
 * Class CreatedAtField.
 */
class CreatedAtField extends DateTimeTzField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(BaseModel::ATTRIBUTE_CREATED_AT);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The date that the resource was created';
    }
}
