<?php

declare(strict_types=1);

namespace App\Http\Api\Field\Admin\FeaturedTheme;

use App\Contracts\Http\Api\Field\CreatableField;
use App\Contracts\Http\Api\Field\UpdatableField;
use App\Enums\Http\Api\Filter\AllowedDateFormat;
use App\Http\Api\Field\DateField;
use App\Http\Api\Schema\Schema;
use App\Models\Admin\FeaturedTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class FeaturedThemeStartAtField.
 */
class FeaturedThemeStartAtField extends DateField implements CreatableField, UpdatableField
{
    /**
     * Create a new field instance.
     *
     * @param  Schema  $schema
     */
    public function __construct(Schema $schema)
    {
        parent::__construct($schema, FeaturedTheme::ATTRIBUTE_START_AT);
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getCreationRules(Request $request): array
    {
        $allowedDateFormats = array_column(AllowedDateFormat::cases(), 'value');

        return [
            'required',
            Str::of('date_format:')
                ->append(implode(',', $allowedDateFormats))
                ->__toString(),
            Str::of('before:')
                ->append($this->resolveEndAt($request))
                ->__toString(),
        ];
    }

    /**
     * Set the update validation rules for the field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request): array
    {
        $allowedDateFormats = array_column(AllowedDateFormat::cases(), 'value');

        return [
            'sometimes',
            'required',
            Str::of('date_format:')
                ->append(implode(',', $allowedDateFormats))
                ->__toString(),
            Str::of('before:')
                ->append($this->resolveEndAt($request))
                ->__toString(),
        ];
    }

    /**
     * Get dependent end_at field.
     *
     * @param  Request  $request
     * @return string|null
     */
    private function resolveEndAt(Request $request): ?string
    {
        if ($request->has(FeaturedTheme::ATTRIBUTE_END_AT)) {
            return $request->get(FeaturedTheme::ATTRIBUTE_END_AT);
        }

        /** @var FeaturedTheme|null $featuredTheme */
        $featuredTheme = $request->route('featuredtheme');

        return $featuredTheme?->end_at?->format(AllowedDateFormat::YMDHISU->value);
    }
}
