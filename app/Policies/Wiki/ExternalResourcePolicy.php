<?php

declare(strict_types=1);

namespace App\Policies\Wiki;

use App\Enums\Auth\CrudPermission;
use App\Enums\Auth\ExtendedCrudPermission;
use App\Models\Auth\User;
use App\Models\Wiki\ExternalResource;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ExternalResourcePolicy.
 */
class ExternalResourcePolicy
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
            return $user !== null && $user->can(CrudPermission::VIEW->format(ExternalResource::class));
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
            return $user !== null && $user->can(CrudPermission::VIEW->format(ExternalResource::class));
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
        return $user->can(CrudPermission::CREATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  ExternalResource  $resource
     * @return bool
     */
    public function update(User $user, ExternalResource $resource): bool
    {
        return !$resource->trashed() && $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  ExternalResource  $resource
     * @return bool
     */
    public function delete(User $user, ExternalResource $resource): bool
    {
        return !$resource->trashed() && $user->can(CrudPermission::DELETE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  ExternalResource  $resource
     * @return bool
     */
    public function restore(User $user, ExternalResource $resource): bool
    {
        return $resource->trashed() && $user->can(ExtendedCrudPermission::RESTORE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->can(ExtendedCrudPermission::FORCE_DELETE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can permanently delete any model.
     *
     * @param  User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can(ExtendedCrudPermission::FORCE_DELETE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach any artist to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachAnyArtist(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach an artist to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachArtist(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can detach an artist from the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function detachArtist(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach any anime to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachAnyAnime(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach an anime to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachAnime(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can detach an anime from the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function detachAnime(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach any studio to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachAnyStudio(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can attach a studio to the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachStudio(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }

    /**
     * Determine whether the user can detach a studio from the resource.
     *
     * @param  User  $user
     * @return bool
     */
    public function detachStudio(User $user): bool
    {
        return $user->can(CrudPermission::UPDATE->format(ExternalResource::class));
    }
}
