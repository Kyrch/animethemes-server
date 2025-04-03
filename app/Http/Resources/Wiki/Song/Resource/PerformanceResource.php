<?php

declare(strict_types=1);

namespace App\Http\Resources\Wiki\Song\Resource;

use App\Http\Api\Schema\Schema;
use App\Http\Api\Schema\Wiki\Song\PerformanceSchema;
use App\Http\Resources\BaseResource;

/**
 * Class PerformanceResource.
 */
class PerformanceResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'performance';

    /**
     * Get the resource schema.
     *
     * @return Schema
     */
    protected function schema(): Schema
    {
        return new PerformanceSchema();
    }
}
