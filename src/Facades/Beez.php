<?php

namespace Mare06xa\Beez\Facades;

use Illuminate\Support\Facades\Facade;

class Beez extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'beez';
    }
}
