<?php

declare(strict_types=1);

namespace App\Http\Api\Field\Billing\Transaction;

use App\Contracts\Http\Api\Field\CreatableField;
use App\Contracts\Http\Api\Field\UpdatableField;
use App\Enums\Models\Billing\Service;
use App\Http\Api\Field\EnumField;
use App\Models\Billing\Transaction;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;

/**
 * Class TransactionServiceField.
 */
class TransactionServiceField extends EnumField implements CreatableField, UpdatableField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Transaction::ATTRIBUTE_SERVICE, Service::class);
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getCreationRules(Request $request): array
    {
        return [
            'required',
            new EnumValue(Service::class),
        ];
    }

    /**
     * Set the update validation rules for the field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request): array
    {
        return [
            'sometimes',
            'required',
            new EnumValue(Service::class),
        ];
    }
}