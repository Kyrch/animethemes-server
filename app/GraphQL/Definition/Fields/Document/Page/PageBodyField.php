<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Document\Page;

use App\GraphQL\Definition\Fields\StringField;
use App\Models\Document\Page;

/**
 * Class PageBodyField.
 */
class PageBodyField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Page::ATTRIBUTE_BODY, nullable: false);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The body content of the resource';
    }
}
