<?php
namespace Otnansirk\Dana\Helpers;

class Calculation
{


    public static $TAX = config("dana.fee_tax", 0.11); // is equal to 11%
    public static $FEES = [
        "CREDIT_CARD"              => [
            "mdr_percent" => config("dana.mdr_percent.credit_card", 0.018),
            // is equal to 1.8%
        ],
        "DEBIT_CARD"               => [
            "mdr_percent" => config("dana.mdr_percent.debit_card", 0.018),
            // is equal to 1.8%
        ],
        "BALANCE"                  => [
            "mdr_percent" => config("dana.mdr_percent.balance", 0.012),
            // is equal to 1.2%
        ],
        "DIRECT_DEBIT_CREDIT_CARD" => [
            "mdr_percent" => config("dana.mdr_percent.direct_debit_credit_card", 0.012),
            // is equal to 1.2%
        ],
        "DIRECT_DEBIT_DEBIT_CARD"  => [
            "mdr_percent" => config("dana.mdr_percent.direct_debit_debit_card", 0.012),
            // is equal to 1.2%
        ],
        "VIRTUAL_ACCOUNT"          => [
            "mdr_before_tax" => config("dana.mdr_before_tax.virtual_account", 2000),
            // is equal to 2000 Rupiah
        ],
        "ONLINE_CREDIT"            => [
            "mdr_percent" => config("dana.mdr_percent.online_credit", 0.012),
            // is equal to 1.2%
        ]
    ];


    /**
     * Get calculation dana fee
     *
     * @param integer $payAmount
     * @param string $payMethod
     * @return array
     */
    public function calculateMDR(int $payAmount, string $payMethod): array
    {
        $mdr = data_get(self::$FEES, "$payMethod.mdr_percent", null);

        $mdrBeforeTax  = ($mdr) ? $mdr * $payAmount : data_get(self::$FEES, "$payMethod.mdr_before_tax", null);
        $taxValue      = $mdrBeforeTax * self::$TAX;
        $mdrIncludeTax = $mdrBeforeTax + $taxValue;
        $settleAmount  = $payAmount - $mdrIncludeTax;

        return [
            "mdr_percent"     => $mdr,
            "mdr_before_tax"  => $mdrBeforeTax,
            "mdr_include_tax" => $mdrIncludeTax,
            "tax_percent"     => self::$TAX,
            "tax"             => $taxValue,
            "payment_method"  => $payMethod,
            "settle_amount"   => $settleAmount
        ];
    }
}