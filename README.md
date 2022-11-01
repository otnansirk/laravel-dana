# Laravel DANACore Packages
This Laravel wrapper/library for DANA Payment API. Visit https://dana.id for more information about the product and see documentation at https://dashboard.dana.id/api-docs for more technical details.

# Installation

#### 1. You can install the package via composer.
```
composer require otnansirk/laravel-dana
```
#### 2. Optional : The service provider will automatically get registered. Or you my manually add the service provider in your `configs/app.php` file.
```
'providers' => [
    // ...
    Otnansirk\Dana\DanaCoreServiceProvider::class,
];
``` 
#### 3. You should publish the `config/dana.php` config file with this php artisan command.
```
php artisan vendor:publish --provider="Otnansirk\Dana\DanaCoreServiceProvider"
```

# How to Use
All config store to `/configs/dana.php`. Customize evrything you need.

## Functions

### 1. Create order | `DANAPay::createOrder($orderData)`
```
<?php

    $orderData = [
        [
        "order" => [
            "orderTitle" => "Dummy product",
            "orderAmount" => [
                "currency" => "IDR",
                "value" => "100"
            ],
            "merchantTransId" => "201505080001",
            "merchantTransType" => "dummy transaction type",
            "orderMemo" => "Memo",
            "goods" => [
                [
                    "merchantGoodsId" => "24525635625623",
                    "description" => "dummy description",
                    "category" => "dummy category",
                    "price" => [
                        "currency" => "IDR",
                        "value" => "100"
                    ],
                    "unit" => "Kg",
                    "quantity" => "3.2",
                    "merchantShippingId" => "564314314574327545",
                    "snapshotUrl" => "[http://snap.url.com]",
                    "extendInfo" => [
                        "sadsad" => "asdasd"
                    ]
                ]
            ]
        ],
        "merchantId" => "216820000000006553000",
        "subMerchantId" => "12345678",
        "productCode" => "51051000100000000001"
    ];

    DANAPay::createOrder($orderData);
```

About all possible payloads for `$orderData` please check the official DANA documentation. <br>
Ref: https://dashboard.dana.id/api-docs/read/33


### 2. Get oAuth URL | DANAPay::generateOauthUrl($terminalType, $redirectUrl);
```
<?php

    $terminalType = "WEB";
    $redirectUrl  = "https://your-app-url.com/oauth/callback"
    DANAPay::generateOauthUrl($terminalType, $redirectUrl)
```
For more information please check the official DANA documentation. <br>
Ref: https://dashboard.dana.id/api-docs/read/47


### 3. Get Token and Refresh Token | DANAPay::getToken($authToken)
```
<?php

    $authToken = "1XKOcVOInfUeZxsu9UMZD3QCM9BCmnbHS7fm73";
    DANAPay::getToken($authToken);
```
You can get value of `$authToken` from oAuth callback proccess. <br>
From this function you will receive `token` and `refresh_token`. <br>
Ref: https://dashboard.dana.id/api-docs/read/32


### 4. Get User Profile | DANAPay::profile($accessToken)
```
<?php

    $accessToken = "publicpBa869cad0990e4e17a57ecf7c5469a4b2";
    DANAPay::profile($accessToken);
```

You can get value for `$accessToken` from DANAPay::getToken function <br>
Ref: https://dashboard.dana.id/api-docs/read/38

### 5. Unbinding Access Token | DANAPay::unBindAllAccount()
```
<?php

    DANAPay::unBindAllAccount();
```
This function used for revoke or unbind all access token registered from the merchant.<br>
Ref: https://dashboard.dana.id/api-docs/read/46

### 6. Function for provide callback response
```
<?php

    $status = true;
    DANAPay::responseFinishNotifyCallback($status)
```
This function will generate valid response for DANA API.<br>
`$status` is boolean data type.

# Contribution
This project is far from perfect. many DANA APIs that have not been implemented. I would be very happy if any of you could contribute for this project.
