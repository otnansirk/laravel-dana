<?php

namespace Otnansirk\Dana\Facades;

use Illuminate\Support\Facades\Facade;

class DANACalculation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'DANACalculation';
    }
}