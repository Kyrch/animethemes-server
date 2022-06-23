<?php

declare(strict_types=1);

namespace App\Http\Api\Field\Billing\Transaction;

use App\Contracts\Http\Api\Field\CreatableField;
use App\Contracts\Http\Api\Field\UpdatableField;
use App\Http\Api\Field\FloatField;
use App\Models\Billing\Transaction;
use Illuminate\Http\Request;

/**
 * Class TransactionAmountField.
 */
class TransactionAmountField extends FloatField implements CreatableField, UpdatableField
{
    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        parent::__construct(Transaction::ATTRIBUTE_AMOUNT);
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
            'regex:/^\-?\d+(\.\d{1,2})?$/',
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
            'regex:/^\-?\d+(\.\d{1,2})?$/',
        ];
    }
}