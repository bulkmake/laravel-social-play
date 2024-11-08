<?php

namespace Bulkmake\LaravelSocialPlay\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelSocialPlay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-social-play';
    }
}
