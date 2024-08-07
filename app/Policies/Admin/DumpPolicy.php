<?php

declare(strict_types=1);

namespace App\Policies\Admin;

use App\Enums\Auth\CrudPermission;
use App\Enums\Auth\ExtendedCrudPermission;
use App\Models\Admin\Dump;
use App\Models\Auth\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class DumpPolicy.
 */
class DumpPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User|null  $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        if (Filament::isServing()) {
            return $user !== null && $user->can(CrudPermission::VIEW->format(Dump::class));
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User|null  $user
     * @return bool
     */
    public function view(?User $user): bool
    {
        if (Filament::isServing()) {
            return $user !== null && $user->can(CrudPermission::VIEW->format(Dump::class));
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CrudPermission::CREATE->format(Dump::class));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Dump  $dump
     * @return bool
     */
    public function update(User $user, Dump $dump): bool
    {
        return ! $dump->trashed() && $user->can(CrudPermission::UPDATE->format(Dump::class));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Dump  $dump
     * @return bool
     */
    public function delete(User $user, Dump $dump): bool
    {
        return ! $dump->trashed() && $user->can(CrudPermission::DELETE->format(Dump::class));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Dump  $dump
     * @return bool
     */
    public function restore(User $user, Dump $dump): bool
    {
        return $dump->trashed() && $user->can(ExtendedCrudPermission::RESTORE->format(Dump::class));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->can(ExtendedCrudPermission::FORCE_DELETE->format(Dump::class));
    }

    /**
     * Determine whether the user can permanently delete any model.
     *
     * @param  User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can(ExtendedCrudPermission::FORCE_DELETE->format(Dump::class));
    }
}
