<?php

declare(strict_types=1);

namespace LaravelEnv\LaravelEnv;

use Illuminate\Support\Facades\Facade;

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
