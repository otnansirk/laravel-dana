<?php

namespace Otnansirk\Dana\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Otnansirk\Dana\Services\DANACoreService api(string $path, array $heads = [], array $bodys = [])
 * @method static array getReqHeader()
 * @method static array getResHeader()
 * @method static string signSignature(array $data)
 * @method static \Illuminate\Http\Client\Response all()
 * @method static object message()
 * @method static \Illuminate\Support\Collection body()
 * @method static int|false verifySignature(array $data, string $signature)
 */
class DANACore extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'DANACore';
    }
}
