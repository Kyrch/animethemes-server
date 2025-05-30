<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\Wiki\Anime\AnimeYear;

use App\GraphQL\Definition\Fields\Field;
use App\GraphQL\Definition\Types\Wiki\AnimeType;
use GraphQL\Type\Definition\Type;

/**
 * Class AnimeYearSummerField.
 */
class AnimeYearSummerField extends Field
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct('summer');
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The summer season of the year';
    }

    /**
     * The type returned by the field.
     *
     * @return Type
     */
    protected function type(): Type
    {
        return Type::listOf(new AnimeType());
    }
}
