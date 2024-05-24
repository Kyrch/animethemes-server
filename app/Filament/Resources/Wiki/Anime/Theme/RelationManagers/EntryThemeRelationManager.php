<?php

declare(strict_types=1);

namespace App\Filament\Resources\Wiki\Anime\Theme\RelationManagers;

use App\Filament\Resources\BaseRelationManager;
use App\Filament\Resources\Wiki\Anime\Theme\Entry as EntryResource;
use App\Models\Wiki\Anime\Theme\AnimeThemeEntry;
use App\Models\Wiki\Anime\AnimeTheme;
use Filament\Forms\Form;
use Filament\Tables\Table;

/**
 * Class EntryThemeRelationManager.
 */
class EntryThemeRelationManager extends BaseRelationManager
{
    /**
     * The relationship the relation manager corresponds to.
     *
     * @return string
     */
    protected static string $relationship = AnimeTheme::RELATION_ENTRIES;

    /**
     * The form to the actions.
     *
     * @param  Form  $form
     * @return Form
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function form(Form $form): Form
    {
        return EntryResource::form($form);
    }

    /**
     * The index page of the resource.
     *
     * @param  Table  $table
     * @return Table
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading(EntryResource::getPluralLabel())
            ->modelLabel(EntryResource::getLabel())
            ->recordTitleAttribute(AnimeThemeEntry::ATTRIBUTE_VERSION)
            ->inverseRelationship(AnimeThemeEntry::RELATION_THEME)
            ->columns(EntryResource::table($table)->getColumns())
            ->defaultSort(AnimeThemeEntry::TABLE.'.'.AnimeThemeEntry::ATTRIBUTE_ID, 'desc')
            ->filters(static::getFilters())
            ->filtersFormMaxHeight('400px')
            ->headerActions(static::getHeaderActions())
            ->actions(static::getActions())
            ->bulkActions(static::getBulkActions());
    }

    /**
     * Get the filters available for the relation.
     *
     * @return array
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function getFilters(): array
    {
        return array_merge(
            parent::getFilters(),
            [],
        );
    }

    /**
     * Get the actions available for the relation.
     *
     * @return array
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function getActions(): array
    {
        return array_merge(
            parent::getActions(),
            [],
        );
    }

    /**
     * Get the bulk actions available for the relation.
     * 
     * @return array
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function getBulkActions(): array
    {
        return array_merge(
            parent::getBulkActions(),
            [],
        );
    }

    /**
     * Get the header actions available for the relation.
     *
     * @return array
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function getHeaderActions(): array
    {
        return array_merge(
            parent::getHeaderActions(),
            [],
        );
    }
}