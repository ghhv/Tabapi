<?php

namespace Mrgla55\Tabapi\Providers\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Tabapi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tabapi';
    }
}
