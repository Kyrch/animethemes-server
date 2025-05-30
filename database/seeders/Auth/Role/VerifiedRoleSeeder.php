<?php

declare(strict_types=1);

namespace Database\Seeders\Auth\Role;

use App\Enums\Auth\CrudPermission;
use App\Enums\Auth\ExtendedCrudPermission;
use App\Enums\Auth\Role as RoleEnum;
use App\Models\Auth\Role;
use App\Models\List\External\ExternalEntry;
use App\Models\List\ExternalProfile;
use App\Models\List\Playlist;
use App\Models\List\Playlist\PlaylistTrack;
use App\Models\User\Like;

/**
 * Class VerifiedRoleSeeder.
 */
class VerifiedRoleSeeder extends RoleSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function run(): void
    {
        $roleEnum = RoleEnum::VERIFIED;

        /** @var Role $role */
        $role = Role::findOrCreate($roleEnum->value);

        $extendedCrudPermissions = array_merge(
            CrudPermission::cases(),
            ExtendedCrudPermission::cases(),
        );

        // User Resources
        $this->configureResource($role, Like::class, [CrudPermission::VIEW, CrudPermission::CREATE, CrudPermission::DELETE]);

        // List Resources
        $this->configureResource($role, ExternalEntry::class, [CrudPermission::VIEW]);
        $this->configureResource($role, ExternalProfile::class, $extendedCrudPermissions);
        $this->configureResource($role, Playlist::class, $extendedCrudPermissions);
        $this->configureResource($role, PlaylistTrack::class, $extendedCrudPermissions);

        $role->color = $roleEnum->color();
        $role->priority = $roleEnum->priority();
        $role->default = true;

        $role->save();
    }
}
