<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Pivot\Wiki\ArtistMember;

use App\GraphQL\Definition\Fields\StringField;
use App\Pivots\Wiki\ArtistMember;

/**
 * Class ArtistMemberAsField.
 */
class ArtistMemberAsField extends StringField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(ArtistMember::ATTRIBUTE_AS);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Used to distinguish member by character';
    }
}
