<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Wiki\Video\Script;

use App\Http\Api\Query\Base\EloquentWriteQuery;
use App\Http\Api\Query\Wiki\Video\Script\ScriptWriteQuery;
use App\Http\Requests\Api\Base\EloquentForceDeleteRequest;

/**
 * Class ScriptForceDeleteRequest.
 */
class ScriptForceDeleteRequest extends EloquentForceDeleteRequest
{
    /**
     * Get the validation API Query.
     *
     * @return EloquentWriteQuery
     */
    public function getQuery(): EloquentWriteQuery
    {
        return new ScriptWriteQuery($this->validated());
    }
}
