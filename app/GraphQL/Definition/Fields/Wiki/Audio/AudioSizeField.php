<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Wiki\Audio;

use App\GraphQL\Definition\Fields\IntField;
use App\Models\Wiki\Audio;

/**
 * Class AudioSizeField.
 */
class AudioSizeField extends IntField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Audio::ATTRIBUTE_SIZE, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The size of the file in storage in Bytes';
    }
}
