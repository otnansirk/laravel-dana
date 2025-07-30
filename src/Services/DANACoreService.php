<?php

namespace Otnansirk\Dana\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Otnansirk\Dana\Exception\DANACoreException;
use Otnansirk\Dana\Exception\DANASignSignatureException;
use Otnansirk\Dana\Exception\DANAVerifySignatureException;

class DANACoreService
{
    private static ?Response $danaData = null;
    private static array $heads = [];
    private static array $bodys = [];

    /**
     * Initialize Request Header
     */
    public static function getReqHeader(): array
    {
        return [
            "version"      => config('dana.version'),
            "clientId"     => config('dana.client_id'),
            "clientSecret" => config('dana.client_secret'),
            "reqTime"      => date(config("dana.date_format")),
            "reqMsgId"     => Str::uuid()->toString(),
            "reserve"      => "{}"
        ];
    }

    /**
     * Initialize Response Header
     */
    public static function getResHeader(): array
    {
        return [
            "version"  => config('dana.version'),
            "clientId" => config('dana.client_id'),
            "respTime" => date(config("dana.date_format")),
            "reqMsgId" => Str::uuid()->toString(),
        ];
    }

    /**
     * Main api function to call to DANA
     */
    public static function api(string $path, array $heads = [], array $bodys = []): self
    {
        $defaultHead      = self::getReqHeader();
        $request          = [
            "head" => array_merge($defaultHead, $heads),
            "body" => $bodys
        ];
        $payloadParsedAry = [
            "request"   => $request,
            "signature" => self::signSignature($request)
        ];

        $res = Http::post(config('dana.api_url') . $path, $payloadParsedAry);

        Log::info("Request DANA To " . config('dana.api_url'));
        Log::info($payloadParsedAry);
        Log::info("Response DANA");
        Log::info($res->json());

        if ($res->failed()) {
            Log::critical("Error when request dana dana.oauth.auth.applyToken");
            Log::critical($res->json());
            throw new DANACoreException("Error Processing Request DANA", 400);
        }

        self::$danaData = $res;
        self::$heads    = $heads;
        self::$bodys    = $bodys;
        return new self;
    }

    /**
     * Return all response from http client as is
     */
    public function all(): Response
    {
        return self::$danaData;
    }

    /**
     * Return only message code and status from dana API
     */
    public function message(): object
    {
        $data = json_decode(self::$danaData->body())->response;

        return (object) [
            "code"   => ($data->body->resultInfo->resultCode !== "SUCCESS") ? 400 : 200,
            "status" => $data->body->resultInfo->resultCode,
            "msg"    => $data->body->resultInfo->resultMsg
        ];
    }

    /**
     * Return data body with format object json
     */
    public function body(): Collection
    {
        $msg  = (array) $this->message();
        $resp = collect((array) json_decode(self::$danaData->body())->response);
        $data = collect($resp->get('body'))
            ->put(
                'transactionTime',
                $resp->get('head')->respTime
            );
        return (collect($msg)->merge($data->toArray()));
    }

    /**
     * Sign signature
     * See this doc API DANA
     * https://dashboard.dana.id/api-docs/read/45
     */
    public static function signSignature(array $data): string
    {
        $signature  = '';
        $privateKey = config("dana.ssh_private_key", "");
        if (!$privateKey) {
            throw new DANASignSignatureException("Please set your app private key. SSH Private Key");
        }
        openssl_sign(
            json_encode($data),
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        return base64_encode($signature);
    }

    /**
     * Verify signature
     * @param array $data string data in json
     * @param string $signature string of signature in base64 encoded
     *
     * @return int|false base 64 signature
     */
    public function verifySignature(array $data, string $signature): int|false
    {
        $publicKey = config("dana.ssh_public_key", "");
        if (!$publicKey) {
            throw new DANAVerifySignatureException("Please set your dana public key");
        }
        $binarySignature = base64_decode($signature);

        return openssl_verify(
            json_encode($data),
            $binarySignature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );
    }
}