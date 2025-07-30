<?php

namespace Otnansirk\Dana\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateOrder
{
    public array $order;
    public string $merchantId;
    public string $productCode;
    public array $envInfo;
    public string $currency;
    public array $body;
    public array $shopInfo;
    public array $paymentPreference;

    public function __construct(array $body)
    {
        $this->currency          = "IDR";
        $this->body              = $body;
        $this->order             = Arr::get($body, "order", []);
        $this->envInfo           = Arr::get($body, "envInfo", []);
        $this->shopInfo          = Arr::get($body, "shopInfo", []);
        $this->productCode       = Arr::get($body, "productCode", "");
        $this->merchantId        = config("dana.merchant_id", "");
        $this->paymentPreference = Arr::get($body, "paymentPreference", []);
    }

    /**
     * Get all payload
     */
    public function payload(): array
    {
        $mandatoryPayload = [
            "order"            => $this->order(),
            "merchantId"       => $this->merchantId,
            "productCode"      => $this->productCode,
            "envInfo"          => $this->envInfo(),
            "notificationUrls" => $this->notificationUrls()
        ];

        return array_merge(
            $mandatoryPayload,
            $this->shopInfo(),
            $this->mcc(),
            $this->paymentPreference()
        );
    }

    /**
     * Order mapper
     */
    protected function order(): array
    {
        $goods = collect(Arr::get($this->order, "goods", []))->map(function ($good) {
            return [
                "merchantGoodsId"    => Arr::get($good, "merchantGoodsId", ""),
                "description"        => Arr::get($good, "description", ""),
                "category"           => Arr::get($good, "category", ""),
                "price"              => [
                    "currency" => Arr::get($good, "price.currency", $this->currency),
                    "value"    => Arr::get($good, "price.value", 0)
                ],
                "unit"               => Arr::get($good, "unit", ""),
                "quantity"           => Arr::get($good, "quantity", ""),
                "snapshotUrl"        => Arr::get($good, "snapshotUrl", "[]"),
                "merchantShippingId" => Arr::get($good, "merchantShippingId", ""),
                "extendInfo"         => Arr::get($good, "extendInfo", ""),
            ];
        })->toArray();

        $shippingInfo = collect(Arr::get($this->order, "shippingInfo", []))->map(function ($shippingInfo) {
            return [
                "merchantShippingId" => Arr::get($shippingInfo, "merchantShippingId", ""),
                "trackingNo"         => Arr::get($shippingInfo, "trackingNo", ""),
                "carrier"            => Arr::get($shippingInfo, "carrier", ""),
                "chargeAmount"       => [
                    "currency" => Arr::get($shippingInfo, "chargeAmount.currency", ""),
                    "value"    => Arr::get($shippingInfo, "chargeAmount.value", "")
                ],
                "countryName"        => Arr::get($shippingInfo, "countryName", ""),
                "stateName"          => Arr::get($shippingInfo, "stateName", ""),
                "cityName"           => Arr::get($shippingInfo, "cityName", ""),
                "areaName"           => Arr::get($shippingInfo, "areaName", ""),
                "address1"           => Arr::get($shippingInfo, "address1", ""),
                "address2"           => Arr::get($shippingInfo, "address2", ""),
                "firstName"          => Arr::get($shippingInfo, "firstName", ""),
                "lastName"           => Arr::get($shippingInfo, "lastName", ""),
                "mobileNo"           => Arr::get($shippingInfo, "mobileNo", ""),
                "phoneNo"            => Arr::get($shippingInfo, "phoneNo", ""),
                "zipCode"            => Arr::get($shippingInfo, "zipCode", ""),
                "email"              => Arr::get($shippingInfo, "email", ""),
                "faxNo"              => Arr::get($shippingInfo, "faxNo", "")
            ];
        })->toArray();

        $orderData = [
            "orderTitle"        => Arr::get($this->order, "orderTitle", ""),
            "orderAmount"       => [
                "currency" => Arr::get($this->order, "orderAmount.currency", $this->currency),
                "value"    => Arr::get($this->order, "orderAmount.value", 0)
                // Default in Dana is use cent. so is value is 100 its equivalent to 1 Rp
                // Ref: https://dashboard.dana.id/api-docs/read/31#Money
            ],
            "merchantTransId"   => Arr::get($this->order, "merchantTransId", Str::uuid()->toString()),
            "merchantTransType" => Arr::get($this->order, "merchantTransType", "APP"),
            "orderMemo"         => Arr::get($this->order, "orderMemo", ""),
            "createdTime"       => Arr::get(
                $this->order,
                "createdTime",
                Carbon::now()->format(config("dana.date_format"))
            ),
            "expiryTime"        => Arr::get(
                $this->order,
                "expiryTime",
                Carbon::now()
                    ->addMinutes(config("dana.expired_after", 60))
                    ->format(config("dana.date_format"))
            ),
            "goods"             => $goods,
            "shippingInfo"      => $shippingInfo
        ];

        return $orderData;
    }

    /**
     * Env info
     */
    protected function envInfo(): array
    {
        return [
            'terminalType'       => Arr::get($this->envInfo, "terminalType", "SYSTEM"),
            'osType'             => Arr::get($this->envInfo, "osType", ""),
            'extendInfo'         => Arr::get($this->envInfo, "extendInfo", ""),
            'orderOsType'        => Arr::get($this->envInfo, "orderOsType", ""),
            'sdkVersion'         => Arr::get($this->envInfo, "sdkVersion", ""),
            'websiteLanguage'    => Arr::get($this->envInfo, "websiteLanguage", ""),
            'tokenId'            => Arr::get($this->envInfo, "tokenId", ""),
            'sessionId'          => Arr::get($this->envInfo, "sessionId", ""),
            'appVersion'         => Arr::get($this->envInfo, "appVersion", ""),
            'merchantAppVersion' => Arr::get($this->envInfo, "merchantAppVersion", ""),
            'clientKey'          => Arr::get($this->envInfo, "clientKey", ""),
            'orderTerminalType'  => Arr::get($this->envInfo, "orderTerminalType", "SYSTEM"),
            'clientIp'           => Arr::get($this->envInfo, "clientIp", ""),
            'sourcePlatform'     => Arr::get($this->envInfo, "sourcePlatform", "IPG")
        ];
    }

    /**
     * Notification url
     */
    public function notificationUrls(): array
    {
        return Arr::get($this->body, "notificationUrls", [
            [
                "url"  => config("dana.pay_return_url"),
                "type" => "PAY_RETURN"
            ],
            [
                "url"  => config("dana.order_notify_url"),
                "type" => "NOTIFICATION"
            ]
        ]);
    }

    /**
     * Shoping info
     */
    public function shopInfo(): array
    {
        if (collect($this->shopInfo)->isNotEmpty()) {
            return [
                "shopInfo" => [
                    "shopId"     => Arr::get($this->shopInfo, "shopId", ""),
                    "operatorId" => Arr::get($this->shopInfo, "operatorId", "")
                ]
            ];
        }
        return [];
    }

    /**
     * Mcc
     */
    public function mcc(): array
    {
        if (Arr::get($this->body, "mcc", null)) {
            return [
                "mcc" => Arr::get($this->body, "mcc", "")
            ];
        }
        return [];
    }

    /**
     * Payment preference
     */
    public function paymentPreference(): array
    {
        if (collect($this->paymentPreference)->isNotEmpty()) {
            $payOptionBills = collect(Arr::get($this->paymentPreference, "payOptionBills", []))
                ->map(
                    function ($payOption) {
                        return [
                            "payOption"        => Arr::get($payOption, "payOption", ""),
                            "payMethod"        => Arr::get($payOption, "payMethod", ""),
                            "transAmount"      => [
                                "currency" => Arr::get($payOption, "transAmount.currency", ""),
                                "value"    => Arr::get($payOption, "transAmount.value", "")
                            ],
                            "chargeAmount"     => [
                                "currency" => Arr::get($payOption, "chargeAmount.currency", ""),
                                "value"    => Arr::get($payOption, "chargeAmount.value", "")
                            ],
                            "payerAccountNo"   => Arr::get($payOption, "payerAccount", ""),
                            "cardCacheToken"   => Arr::get($payOption, "cardCacheToken", ""),
                            "saveCardAfterPay" => Arr::get($payOption, "saveCardAfterPay", ""),
                            "channelInfo"      => Arr::get($payOption, "channelInfo", ""),
                            "issuingCountry"   => Arr::get($payOption, "issuingCountry", ""),
                            "assetType"        => Arr::get($payOption, "assetType", ""),
                            "extendInfo"       => Arr::get($payOption, "extendInfo", "")
                        ];
                    }
                )->toArray();

            return [
                "paymentPreference" => [
                    "disabledPayMethods" => Arr::get($this->paymentPreference, "disabledPayMethods", ""),
                    "payOptionBills"     => $payOptionBills
                ]
            ];
        }
        return [];
    }
}