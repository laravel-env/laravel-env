<?php

namespace LaravelEnv\LaravelEnv;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelEnv\LaravelEnv\Skeleton\SkeletonClass
 */
class EnvFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-env';
    }
}
