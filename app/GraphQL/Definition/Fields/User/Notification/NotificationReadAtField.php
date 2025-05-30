<?php

declare(strict_types=1);

namespace App\GraphQL\Definition\Fields\User\Notification;

use App\GraphQL\Definition\Fields\DateTimeTzField;
use App\Models\User\Notification;

/**
 * Class NotificationReadAtField.
 */
class NotificationReadAtField extends DateTimeTzField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Notification::ATTRIBUTE_READ_AT);
    }

    /**
     * The description of the field.
     *
     * @return string
     */
    public function description(): string
    {
        return 'The date that the user read the notification';
    }
}
