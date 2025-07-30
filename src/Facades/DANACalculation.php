<?php

namespace Otnansirk\Dana\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method float taxValue()
 * @method array fees()
 * @method array calculateMDR(int $payAmount, string $payMethod)
 */
class DANACalculation extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'DANACalculation';
    }
}