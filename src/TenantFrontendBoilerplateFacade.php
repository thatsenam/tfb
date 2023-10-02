<?php

namespace Codeintel\TenantFrontendBoilerplate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Codeintel\TenantFrontendBoilerplate\Skeleton\SkeletonClass
 */
class TenantFrontendBoilerplateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tenant-frontend-boilerplate';
    }
}
