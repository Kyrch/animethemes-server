<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Types\Pivot\Wiki;

use App\Contracts\GraphQL\HasFields;
use App\Contracts\GraphQL\HasRelations;
use App\GraphQL\Definition\Fields\Base\CreatedAtField;
use App\GraphQL\Definition\Fields\Base\UpdatedAtField;
use App\GraphQL\Definition\Fields\Field;
use App\GraphQL\Definition\Relations\BelongsToRelation;
use App\GraphQL\Definition\Relations\Relation;
use App\GraphQL\Definition\Types\Pivot\PivotType;
use App\GraphQL\Definition\Types\Wiki\AnimeType;
use App\GraphQL\Definition\Types\Wiki\ImageType;
use App\Pivots\Wiki\AnimeImage;

/**
 * Class AnimeImageType.
 */
class AnimeImageType extends PivotType implements HasFields, HasRelations
{
    /**
     * The description of the type.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Represents the association between an anime and an image.';
    }

    /**
     * The relations of the type.
     *
     * @return array<int, Relation>
     */
    public function relations(): array
    {
        return [
            new BelongsToRelation(new AnimeType(), AnimeImage::RELATION_ANIME, nullable: false),
            new BelongsToRelation(new ImageType(), AnimeImage::RELATION_IMAGE, nullable: false),
        ];
    }

    /**
     * The fields of the type.
     *
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            new CreatedAtField(),
            new UpdatedAtField(),
        ];
    }
}
