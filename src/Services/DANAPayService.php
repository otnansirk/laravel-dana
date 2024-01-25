<?php
namespace Otnansirk\Dana\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Otnansirk\Dana\Facades\DANACore;
use Otnansirk\Dana\Helpers\Calculation;
use Otnansirk\Dana\Helpers\CreateOrder;
use Otnansirk\Dana\Validation\Validation;
use Otnansirk\Dana\Exception\DANAException;
use Otnansirk\Dana\Exception\DANACreateOrderException;
use Otnansirk\Dana\Exception\DANAPayGetTokenException;
use Otnansirk\Dana\Exception\DANAPayUnBindingAllException;

class DANAPayService
{

    /**
     * Binding account to dana
     *
     * @param string $authCode
     * @return Collection
     */
    public static function getToken(string $authCode): Collection
    {
        $path  = "/dana/oauth/auth/applyToken.htm";
        $heads = [
            "function" => "dana.oauth.auth.applyToken"
        ];
        $bodys = [
            "grantType" => "AUTHORIZATION_CODE",
            "authCode"  => $authCode
        ];

        $data = DanaCore::api($path, $heads, $bodys);
        if ($data->message()->status !== "SUCCESS") {
            throw new DANAPayGetTokenException($data->message()->msg, $data->message()->code);
        }

        return collect([
            "token"         => $data->body()->get('accessTokenInfo')->accessToken,
            "refresh_token" => $data->body()->get('accessTokenInfo')->accessToken,
            "expires_in"    => $data->body()->get('accessTokenInfo')->expiresIn,
            "status"        => $data->body()->get('accessTokenInfo')->tokenStatus
        ]);
    }

    /**
     * Unbind access token use for merchant to revoke all tokens registered for its user
     *
     * @return Collection
     */
    public function unBindAllAccount(): Collection
    {
        $path  = "/dana/oauth/unbind/revokeAllTokens.htm";
        $heads = [
            "function" => "dana.oauth.unbind.revokeAllTokens"
        ];
        $bodys = [
            "merchantId" => config("dana.merchant_id")
        ];

        $data = DanaCore::api($path, $heads, $bodys);
        if ($data->message()->status !== "SUCCESS") {
            throw new DANAPayUnBindingAllException($data->message()->msg, $data->message()->code);
        }
        return collect($data->message());
    }

    /**
     * Get user profile
     *
     * @param string $accessToken
     * @return Collection
     */
    public function profile(string $accessToken): Collection
    {
        $path  = "/dana/member/query/queryUserProfile.htm";
        $heads = [
            "function"    => "dana.member.query.queryUserProfile",
            "accessToken" => $accessToken
        ];
        $bodys = [
            "userResources" => config("dana.user_resources"),
        ];
        $data  = DanaCore::api($path, $heads, $bodys);

        if ($data->message()->status !== "SUCCESS") {
            throw new DANAException($data->message()->msg, $data->message()->code);
        }

        $res = collect($data->body()->get('userResourceInfos'))
            ->map(function ($val) {
                return [strtolower($val->resourceType) => $val->value];
            })
            ->flatMap(function ($values) {
                return $values;
            });
        $res->put('topup_url', $res->get("topup_url") . "?ott=" . $res->get("ott"));
        $res->put('transaction_url', $res->get("transaction_url") . "?ott=" . $res->get("ott"));
        $res->forget('ott');

        return $res;

    }

    /**
     * Create order
     *
     * @param array $bodys
     * @return Collection
     */
    public function createOrder(array $bodys): Collection
    {

        $path  = "/dana/acquiring/order/createOrder.htm";
        $heads = [
            "function" => "dana.acquiring.order.createOrder"
        ];

        $orderData = new CreateOrder($bodys);
        $payload   = $orderData->payload();
        $res       = DanaCore::api($path, $heads, $payload);

        if ($res->message()->status !== "SUCCESS") {
            throw new DANACreateOrderException("DANA " . $res->message()->msg, $res->message()->code);
        }

        return $res->body()
            ->forget(["resultInfo", "code", "status", "msg"])
            ->map(function ($val, $key) {
                $data = ($key === 'transactionTime') ? \Carbon\Carbon::parse($val) : $val;
                return [$key => $data];
            })
            ->flatMap(function ($values) {
                return $values;
            });
    }


    /**
     * To query transaction detail by DANA's acquirementId or merchantTransId.
     * When DANA's acquirementId and merchantTransId are both provided, this API assigns
     * a higher priority to DANA's acquirementId, merchantTransId would be ignored.
     *
     * @param string $acquirementId
     * @return Collection
     */
    public function queryOrder(string $acquirementId): Collection
    {

        $path  = "/dana/acquiring/order/query.htm";
        $heads = [
            "function" => "dana.acquiring.order.query",
        ];

        $payload = [
            "merchantId"    => config("dana.merchant_id", ""),
            "acquirementId" => $acquirementId
        ];
        $res     = DanaCore::api($path, $heads, $payload);
        return collect([
            "code"            => data_get($res->body(), 'code', 200),
            "message"         => data_get($res->body(), 'msg', ""),
            "goods"           => data_get($res->body(), 'goods', ""),
            "status"          => data_get($res->body(), 'resultInfo', ""),
            "acquirementId"   => data_get($res->body(), 'acquirementId', ""),
            "merchantTransId" => data_get($res->body(), 'merchantTransId', ""),
        ]);
    }


    /**
     * Generate url oauth
     *
     * @param string $terminalType
     * @param string $redirectUrl
     * @return string
     */
    public function generateOauthUrl(string $terminalType = "WEB", string $redirectUrl = ""): string
    {
        Validation::terminalType($terminalType);

        $baseAPIUrl = config("dana.web_url");
        $path       = "/d/portal/oauth?";
        $params     = [
            "clientId"     => config("dana.client_id"),
            "scopes"       => config("dana.oauth_scopes"),
            "requestId"    => Str::uuid()->toString(),
            "terminalType" => $terminalType,
            "redirectUrl"  => $redirectUrl
        ];

        $oauthUrl = $baseAPIUrl . $path;
        $oauthUrl .= http_build_query($params);

        return $oauthUrl;
    }

    /**
     * Response for finish payment notify callback
     * @param boolean $status
     * @return array
     */
    public function responseFinishNotifyCallback($status = true): array
    {
        $header = DanaCore::getResHeader();

        $resultInfo = [
            "resultStatus" => "S",
            "resultCodeId" => "00000000",
            "resultCode"   => "SUCCESS",
            "resultMsg"    => "success"
        ];

        if (!$status) {
            $resultInfo = [
                "resultStatus" => "U",
                "resultCodeId" => "00000900",
                "resultCode"   => "SYSTEM_ERROR",
                "resultMsg"    => "System error"
            ];
        }

        $optionalHeader = [
            "function" => "dana.acquiring.order.finishNotify",
        ];
        $body           = [
            "resultInfo" => $resultInfo
        ];
        $response       = [
            "head" => array_merge($header, $optionalHeader),
            "body" => $body
        ];

        return [
            "response"  => $response,
            "signature" => DanaCore::signSignature($response)
        ];
    }

}