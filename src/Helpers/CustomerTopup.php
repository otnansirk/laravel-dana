<?php

namespace Otnansirk\Dana\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


class CustomerTopup
{
    public $order;
    public $merchantId;
    public $productCode;
    public $envInfo;
    public $currency;
    public $body;
    public $shopInfo;
    public $paymentPreference;

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
     *
     * @return array
     */
    public function payload(): array
    {
        $mandatoryPayload = [
            "partnerReferenceNo" => $this->partnerReferenceNo,
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

}