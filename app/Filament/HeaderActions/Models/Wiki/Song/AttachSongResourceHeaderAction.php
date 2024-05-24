<?php

declare(strict_types=1);

namespace App\Filament\HeaderActions\Models\Wiki\Song;

use App\Actions\Models\Wiki\Song\AttachSongResourceAction as AttachSongResourceActionAction;
use App\Enums\Models\Wiki\ResourceSite;
use App\Filament\HeaderActions\Models\Wiki\AttachResourceHeaderAction;
use App\Models\Wiki\Song;
use App\Rules\Wiki\Resource\SongResourceLinkFormatRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class AttachSongResourceHeaderAction.
 */
class AttachSongResourceHeaderAction extends AttachResourceHeaderAction
{
    /**
     * Initial setup for the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(fn (Song $record, array $data) => (new AttachSongResourceActionAction($this->sites))->handle($record, $data));
    }

    /**
     * Get the format validation rule.
     *
     * @param  ResourceSite  $site
     * @return ValidationRule
     */
    protected function getFormatRule(ResourceSite $site): ValidationRule
    {
        return new SongResourceLinkFormatRule($site);
    }
}