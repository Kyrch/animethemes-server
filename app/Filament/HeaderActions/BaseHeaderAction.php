<?php

declare(strict_types=1);

namespace App\Filament\HeaderActions;

use Filament\Actions\Action;

/**
 * Class BaseHeaderAction.
 *
 * Header actions are present at the top of the edit/view model page.
 */
abstract class BaseHeaderAction extends Action
{
    /**
     * Initial setup for the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
    }
}