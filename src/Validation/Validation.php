<?php

namespace Otnansirk\Dana\Validation;

use Otnansirk\Dana\Exception\DANAException;

class Validation
{
    /**
     * Validate terminal type
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
