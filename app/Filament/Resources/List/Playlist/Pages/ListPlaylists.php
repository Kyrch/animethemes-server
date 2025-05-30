<?php

declare(strict_types=1);

namespace App\Filament\Resources\List\Playlist\Pages;

use App\Filament\Resources\Base\BaseListResources;
use App\Filament\Resources\List\Playlist;
use App\Models\List\Playlist as PlaylistModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ListPlaylists.
 */
class ListPlaylists extends BaseListResources
{
    protected static string $resource = Playlist::class;

    /**
     * Get the header actions available.
     *
     * @return array
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
        ];
    }

    /**
     * Using Laravel Scout to search.
     *
     * @param  Builder  $query
     * @return Builder
     */
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        return $this->makeScout($query, PlaylistModel::class);
    }
}
