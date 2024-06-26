<?php

declare(strict_types=1);

namespace App\Filament\HeaderActions\Models\Wiki\Artist;

use App\Actions\Models\Wiki\Artist\AttachArtistImageAction as AttachArtistImageActionAction;
use App\Enums\Models\Wiki\ImageFacet;
use App\Filament\HeaderActions\Models\Wiki\AttachImageHeaderAction;
use App\Models\Wiki\Artist;

/**
 * Class AttachArtistImageHeaderAction.
 */
class AttachArtistImageHeaderAction extends AttachImageHeaderAction
{
    /**
     * Initial setup for the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->facets([
            ImageFacet::COVER_SMALL,
            ImageFacet::COVER_LARGE,
        ]);

        $this->action(fn (Artist $record, array $data) => (new AttachArtistImageActionAction($this->facets))->handle($record, $data));
    }
}
