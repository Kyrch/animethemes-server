<?php

declare(strict_types=1);

namespace App\Filament\Actions\Models\Wiki\Anime;

use App\Actions\Models\Wiki\Anime\AttachAnimeResourceAction as AttachAnimeResourceActionAction;
use App\Enums\Models\Wiki\ResourceSite;
use App\Filament\Actions\Models\Wiki\AttachResourceAction;
use App\Models\Wiki\Anime;
use App\Rules\Wiki\Resource\AnimeResourceLinkFormatRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class AttachAnimeResourceAction.
 */
class AttachAnimeResourceAction extends AttachResourceAction
{
    /**
     * Initial setup for the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sites([
            ResourceSite::ANIDB,
            ResourceSite::ANILIST,
            ResourceSite::ANIME_PLANET,
            ResourceSite::ANN,
            ResourceSite::KITSU,
            ResourceSite::MAL,
            ResourceSite::OFFICIAL_SITE,
            ResourceSite::TWITTER,
            ResourceSite::YOUTUBE,
            ResourceSite::WIKI,
        ]);

        $this->action(fn (Anime $record, array $data) => (new AttachAnimeResourceActionAction($this->sites))->handle($record, $data));
    }

    /**
     * Get the format validation rule.
     *
     * @param  ResourceSite  $site
     * @return ValidationRule
     */
    protected function getFormatRule(ResourceSite $site): ValidationRule
    {
        return new AnimeResourceLinkFormatRule($site);
    }
}
