<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Types\Wiki;

use App\GraphQL\Definition\Fields\Base\CreatedAtField;
use App\GraphQL\Definition\Fields\Base\DeletedAtField;
use App\GraphQL\Definition\Fields\Base\IdField;
use App\GraphQL\Definition\Fields\Base\UpdatedAtField;
use App\GraphQL\Definition\Fields\Wiki\ThemeGroup\ThemeGroupNameField;
use App\GraphQL\Definition\Fields\Wiki\ThemeGroup\ThemeGroupSlugField;
use App\GraphQL\Definition\Relations\HasManyRelation;
use App\GraphQL\Definition\Relations\Relation;
use App\GraphQL\Definition\Types\BaseType;
use App\GraphQL\Definition\Types\Wiki\Anime\AnimeThemeType;
use App\Models\Wiki\Group;

/**
 * Class ThemeGroupType.
 */
class ThemeGroupType extends BaseType
{
    /**
     * The description of the type.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return "Represents the group that accompanies a Theme.\n\nFor example, English Version is the group for english dubbed Theme.";
    }

    /**
     * The relations of the type.
     *
     * @return array<int, Relation>
     */
    public function relations(): array
    {
        return [
            new HasManyRelation(new AnimeThemeType(), Group::RELATION_THEMES),
        ];
    }

    /**
     * The directives of the type.
     *
     * @return array
     */
    public function directives(): array
    {
        return [
            'model' => [
                'class' => Group::class,
            ],
        ];
    }

    /**
     * The fields of the type.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            new IdField(Group::ATTRIBUTE_ID),
            new ThemeGroupNameField(),
            new ThemeGroupSlugField(),
            new CreatedAtField(),
            new UpdatedAtField(),
            new DeletedAtField(),
        ];
    }
}
