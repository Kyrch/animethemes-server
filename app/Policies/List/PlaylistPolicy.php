<?php

declare(strict_types=1);

namespace App\Policies\List;

use App\Enums\Auth\CrudPermission;
use App\Enums\Auth\ExtendedCrudPermission;
use App\Enums\Auth\Role as RoleEnum;
use App\Enums\Models\List\PlaylistVisibility;
use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\List\Playlist;
use App\Models\Wiki\Image;
use App\Pivots\List\PlaylistImage;
use App\Policies\BasePolicy;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlaylistPolicy.
 */
class PlaylistPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  User|null  $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        if (Filament::isServing()) {
            return $user !== null && $user->hasRole(RoleEnum::ADMIN->value);
        }

        return $user === null || $user->can(CrudPermission::VIEW->format(Playlist::class));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User|null  $user
     * @param  Playlist  $playlist
     * @return bool
     */
    public function view(?User $user, BaseModel|Model $playlist): bool
    {
        if (Filament::isServing()) {
            return $user !== null && $user->hasRole(RoleEnum::ADMIN->value);
        }

        return $user !== null
            ? ($user->getKey() === $playlist->user_id || PlaylistVisibility::PRIVATE !== $playlist->visibility) && $user->can(CrudPermission::VIEW->format(Playlist::class))
            : PlaylistVisibility::PRIVATE !== $playlist->visibility;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if (Filament::isServing()) {
            return $user->hasRole(RoleEnum::ADMIN->value);
        }

        return $user->can(CrudPermission::CREATE->format(Playlist::class));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Playlist  $playlist
     * @return bool
     */
    public function update(User $user, BaseModel|Model $playlist): bool
    {
        if (Filament::isServing()) {
            return $user->hasRole(RoleEnum::ADMIN->value);
        }

        return !$playlist->trashed() && $user->getKey() === $playlist->user_id && $user->can(CrudPermission::UPDATE->format(Playlist::class));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Playlist  $playlist
     * @return bool
     */
    public function delete(User $user, BaseModel|Model $playlist): bool
    {
        if (Filament::isServing()) {
            return $user->hasRole(RoleEnum::ADMIN->value);
        }

        return !$playlist->trashed() && $user->getKey() === $playlist->user_id && $user->can(CrudPermission::DELETE->format(Playlist::class));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Playlist  $playlist
     * @return bool
     */
    public function restore(User $user, BaseModel|Model $playlist): bool
    {
        if (Filament::isServing()) {
            return $user->hasRole(RoleEnum::ADMIN->value);
        }

        return $playlist->trashed() && $user->getKey() === $playlist->user_id && $user->can(ExtendedCrudPermission::RESTORE->format(Playlist::class));
    }

    /**
     * Determine whether the user can add a track to the playlist.
     *
     * @param  User  $user
     * @return bool
     */
    public function addTrack(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN->value);
    }

    /**
     * Determine whether the user can attach any image to the playlist.
     *
     * @param  User  $user
     * @return bool
     */
    public function attachAnyImage(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN->value);
    }

    /**
     * Determine whether the user can attach an image to the playlist.
     *
     * @param  User  $user
     * @param  Playlist  $playlist
     * @param  Image  $image
     * @return bool
     */
    public function attachImage(User $user, Playlist $playlist, Image $image): bool
    {
        $attached = PlaylistImage::query()
            ->where($image->getKeyName(), $image->getKey())
            ->where($playlist->getKeyName(), $playlist->getKey())
            ->exists();

        return !$attached && $user->hasRole(RoleEnum::ADMIN->value);
    }

    /**
     * Determine whether the user can detach an image from the playlist.
     *
     * @param  User  $user
     * @return bool
     */
    public function detachImage(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN->value);
    }
}
