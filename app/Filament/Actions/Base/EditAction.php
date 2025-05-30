<?php

declare(strict_types=1);

namespace App\Filament\Actions\Base;

use App\Concerns\Filament\ActionLogs\HasActionLogs;
use App\Concerns\Filament\ActionLogs\HasPivotActionLogs;
use App\Filament\RelationManagers\BaseRelationManager;
use App\Filament\Resources\Base\BaseListResources;
use App\Filament\Resources\Base\BaseManageResources;
use App\Models\Admin\ActionLog;
use Filament\Forms\Form;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\EditAction as DefaultEditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class EditAction.
 */
class EditAction extends DefaultEditAction
{
    use HasActionLogs;
    use HasPivotActionLogs;

    /**
     * Initial setup for the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('');
        $this->iconSize(IconSize::Medium);

        $this->form(fn (Form $form, BaseRelationManager|BaseManageResources|BaseListResources $livewire) => [
            ...$livewire->form($form)->getComponents(),
            ...($livewire instanceof BaseRelationManager ? $livewire->getPivotFields() : []),
        ]);

        $this->after(function ($livewire, Model $record, EditAction $action) {
            if ($livewire instanceof BaseListResources) {
                ActionLog::modelUpdated($record);
            }

            if ($livewire instanceof BaseRelationManager) {
                if ($livewire->getRelationship() instanceof BelongsToMany) {
                    $this->pivotActionLog('Update Attached', $livewire, $record, $action);
                }
            }
        });
    }
}
