<?php
namespace Otnansirk\Dana\Validation;

use Illuminate\Support\Arr;
use Otnansirk\Danacore\Exception\DANAException;

class Validation
{
    
    /**
     * Validatae terminal type
     *
     * @param string $type
     * @return boolean
     */
    public static function terminalType(string $type): bool
    {
        $validate = in_array($type, ["WEB", "APP", "WAP", "SYSTEM"]);
        if (!$validate) {
            throw new DANAException("Terminal type is not valid", 400);
        }
        return true;
    }
}
