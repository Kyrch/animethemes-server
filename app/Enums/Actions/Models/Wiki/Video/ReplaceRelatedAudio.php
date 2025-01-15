<?php

declare(strict_types=1);

namespace App\Enums\Actions\Models\Wiki\Video;

use App\Concerns\Enums\LocalizesName;

/**
 * Enum ReplaceRelatedAudio.
 */
enum ReplaceRelatedAudio: int
{
    use LocalizesName;

    case NO = 0;
    case YES = 1;
}