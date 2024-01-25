# Laravel DANACore Packages
This Laravel wrapper/library for DANA Payment API. Visit https://dana.id for more information about the product and see documentation at https://dashboard.dana.id/api-docs for more technical details.

# Installation

#### 1. You can install the package via composer.
```sh
composer require otnansirk/laravel-dana
```

#### 2. Optional : The service provider will automatically get registered. Or you may manually add the service provider in your `configs/app.php` file.
```php
'providers' => [
    // ...
    Otnansirk\Dana\DanaCoreServiceProvider::class,
];
```

#### 3. You should publish the `config/dana.php` config file with this php artisan command.
```sh
php artisan vendor:publish --provider="Otnansirk\Dana\DanaCoreServiceProvider"
```

# How to Use
All configuration are stored in `config/dana.php`. Customize everything you need.

## Functions

### 1. Create order | `DANAPay::createOrder($orderData)`
```php
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
                    "key" => "value",
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

### 2. Get Transaction by acquirementId | DANAPay::queryOrder($acquirementId);
```php
$acquirementId = "20240125111212800110166050101920928";
DANAPay::queryOrder($acquirementId);
```
You can get transaction detail and status transaction with this method <br>
Ref: https://dashboard.dana.id/api-docs/read/42

### 3. Get oAuth URL | DANAPay::generateOauthUrl($terminalType, $redirectUrl);
```php
$terminalType = "WEB";
$redirectUrl  = "https://your-app-url.com/oauth/callback";
DANAPay::generateOauthUrl($terminalType, $redirectUrl);
```

For more information please check the official DANA documentation. <br>
Ref: https://dashboard.dana.id/api-docs/read/47

### 4. Get Token and Refresh Token | DANAPay::getToken($authToken)
```php
$authToken = "your-auth-token";
DANAPay::getToken($authToken);
```

You can get value of `$authToken` from oAuth callback process. <br>
From this function you will receive `token` and `refresh_token`. <br>
Ref: https://dashboard.dana.id/api-docs/read/32

### 5. Get User Profile | DANAPay::profile($accessToken)
```php
$accessToken = "your_user_profile_access_token";
DANAPay::profile($accessToken);
```

You can get value for `$accessToken` from DANAPay::getToken function <br>
Ref: https://dashboard.dana.id/api-docs/read/38

### 6. Unbinding Access Token | DANAPay::unBindAllAccount()
```php
DANAPay::unBindAllAccount();
```

This function used for revoke or unbind all access token registered from the merchant.<br>
Ref: https://dashboard.dana.id/api-docs/read/46

### 7. Function for provide callback response
```php
$status = true;
DANAPay::responseFinishNotifyCallback($status);
```

This function will generate valid response for DANA API.<br>
`$status` is boolean data type.

### 8. Function for calculation MDR
```php
$payAmount = 100000;
$payMethod = 'BALANCE';
DANACalculation::calculateMDR($payAmount, $payMethod);
```

This function will calculate MDR fee for DANA.
You will get value $payMethod and $payAmount from callback DANA.

# Contribution
This project is far from perfect. many DANA APIs that have not been implemented. I would be very happy if any of you could contribute for this project.
