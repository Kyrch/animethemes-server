<?php

declare(strict_types=1);

namespace App\Http\Api\Schema\Auth;

use App\Http\Api\Field\Auth\User\UserNameField;
use App\Http\Api\Field\Base\IdField;
use App\Http\Api\Field\Field;
use App\Http\Api\Include\AllowedInclude;
use App\Http\Api\Schema\EloquentSchema;
use App\Http\Api\Schema\List\PlaylistSchema;
use App\Http\Resources\Auth\Resource\UserResource;
use App\Models\Auth\User;

/**
 * Class UserSchema.
 */
class UserSchema extends EloquentSchema
{
    /**
     * The model this schema represents.
     *
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Get the type of the resource.
     *
     * @return string
     */
    public function type(): string
    {
        return UserResource::$wrap;
    }

    /**
     * Get the allowed includes.
     *
     * @return AllowedInclude[]
     */
    public function allowedIncludes(): array
    {
        return [
            new AllowedInclude(new PlaylistSchema(), User::RELATION_PLAYLISTS),
        ];
    }

    /**
     * Get the direct fields of the resource.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return array_merge(
            parent::fields(),
            [
                new IdField($this, User::ATTRIBUTE_ID),
                new UserNameField($this),
            ],
        );
    }
}
