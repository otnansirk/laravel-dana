<?php

return [

    "version"            => "2.0",

    "env"                => env('DANA_ENV', 'development'),

    "active"             => env('DANA_ACTIVE', false),

    /**
     * True for production
     * false for sandbox mode
     *
     */
    "is_production"      => (env('DANA_ENV', 'development') == 'production') ? true : false,

    /**
     * for the API url value
     * example = https://api-sandbox.saas.dana.id
     *
     */
    "api_url"            => env('DANA_API_URL', 'https://api-sandbox.saas.dana.id'),

    /**
     * for the WEB url value
     * example = https://api-sandbox.saas.dana.id
     *
     */
    "web_url"            => env('DANA_WEB_URL', 'https://m.sandbox.dana.id'),

    /**
     * for clientId value
     * example = 212640060018011593493
     *
     */
    "merchant_id"        => env("DANA_MARCHANT_ID", ""),

    /**
     * for clientId value
     * example = 2018122812174155520063
     *
     */
    "client_id"          => env("DANA_CLIENT_ID", ""),

    /**
     * for clientSecret value
     * example = 3f5798274c9b427e9e0aa2c5db0a6454
     *
     */
    "client_secret"      => env("DANA_CLIENT_SECRET", ""),

    /**
     * for oauthRedirectUrl value
     * Put your redirect url for OAuth flow/account binding, to redirect the authCode
     * example = https://api.merchant.com/oauth-callback
     *
     */
    "oauth_redirect_url" => 'https://api.merchant.com/oauth-callback',

    /**
     * for oauthScopes value
     * Account binding
     *
     */
    "oauth_scopes"       => 'CASHIER,QUERY_BALANCE,DEFAULT_BASIC_PROFILE,MINI_DANA',

    /**
     * for get user profile
     * user resources
     *
     */
    "user_resources"     => [
        "BALANCE",
        "TRANSACTION_URL",
        "MASK_DANA_ID",
        "TOPUP_URL",
        "OTT"
    ],

    /**
     * for refundDestination value
     * Api configuration
     *
     */
    "refund_destination" => 'TO_BALANCE',

    /**
     * For date format
     */
    "date_format"        => "Y-m-d\TH:i:sP",

    /**
     * For expired date after. Unit is minutes
     */
    "expired_after"      => 60,
    // Equivalent to 1 hours

    /**
     * For get notif every status order is changed
     */
    "order_notify_url"   => env("DANA_ORDER_NOTIFY_URL", ""),

    /**
     * For get redirect user to merchant website
     */
    "pay_return_url"     => env("DANA_PAY_RETURN_URL", ""),

    /**
     * Get DANA public key
     */
    "ssh_public_key"     => env("DANA_PUB_KEY", ""),

    /**
     * Get local private key
     */
    "ssh_private_key"    => env("DANA_PRIVATE_KEY", ""),

    /**
     * mdr percent update on 2023
     */
    "mdr_percent"        => [
        /**
         * mdr persent for credit card
         * 0.0018 is equal to 1.8%
         */
        "credit_card"              => env("DANA_MDR_CC", 0.018),

        /**
         * mdr persent for credit card
         * 0.0018 is equal to 1.8%
         */
        "debit_card"               => env("DANA_MDR_DC", 0.018),

        /**
         * mdr persent for balance
         * 0.012 is equal to 1.2%
         */
        "balance"                  => env("DANA_MDR_BALANCE", 0.012),

        /**
         * mdr persent for credit card
         * 0.0012 is equal to 1.2%
         */
        "direct_debit_credit_card" => env("DANA_MDR_DD_CC", 0.012),

        /**
         * mdr persent for credit card
         * 0.0012 is equal to 1.2%
         */
        "direct_debit_debit_card"  => env("DANA_MDR_DD_DC", 0.012),

        /**
         * mdr persent for credit card
         * 0.0012 is equal to 1.2%
         */
        "online_credit"            => env("DANA_MDR_ONLINE_CREDIT", 0.012),

    ],
    "mdr_before_tax"     => [
        /**
         * mdr before tax for virtual account
         * 2000 is equal to 2000 Rupiah
         */
        "online_credit" => env("DANA_MDR_BEFORE_VA", 2000)
    ],

    /**
     * fee tax
     *
     * 0.11 is equal to 11%
     */
    "fee_tax"            => env("DANA_FEE_TAX", 0.11)
];