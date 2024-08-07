<?php

declare(strict_types=1);

namespace App\Policies\Admin;

use App\Enums\Auth\CrudPermission;
use App\Models\Admin\Feature;
use App\Models\Auth\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class FeaturePolicy.
 */
class FeaturePolicy
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
            return $user !== null && $user->can(CrudPermission::VIEW->format(Feature::class));
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User|null  $user
     * @param  Feature  $feature
     * @return bool
     */
    public function view(?User $user, Feature $feature): bool
    {
        if (Filament::isServing()) {
            return $user !== null && $user->can(CrudPermission::VIEW->format(Feature::class));
        }

        return $feature->isNullScope();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CrudPermission::CREATE->format(Feature::class));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(Feature::class));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->can(CrudPermission::DELETE->format(Feature::class));
    }
}
