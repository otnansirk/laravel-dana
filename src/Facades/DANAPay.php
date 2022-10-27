<?php

namespace Otnansirk\Danacore\Facades;

use Illuminate\Support\Facades\Facade;

class DANAPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'DANAPay';
    }
}
