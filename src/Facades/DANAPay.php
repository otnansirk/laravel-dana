<?php

namespace Otnansirk\Dana\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getToken(string $authCode)
 * @method \Illuminate\Support\Collection unBindAllAccount()
 * @method \Illuminate\Support\Collection profile(string $accessToken)
 * @method \Illuminate\Support\Collection createOrder(array $bodys)
 * @method \Illuminate\Support\Collection queryOrder(string $acquirementId)
 * @method string generateOauthUrl(string $terminalType = "WEB", string $redirectUrl = "")
 * @method array responseFinishNotifyCallback(bool $status = true)
 */
class DANAPay extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'DANAPay';
    }
}
