<?php
// return [
//     'tmn_code' => env('VNPAY_TMN_CODE'),
//     'hash_secret' => env('VNPAY_HASH_SECRET'),
//     'url' => env('VNPAY_URL'),
//     'return_url' => env('VNPAY_RETURN_URL'),
// ];
return [
    'tmn_code' => env('VNPAY_TMN_CODE'),
    'hash_secret' => env('VNPAY_HASH_SECRET'),
    'payment_url' => env('VNPAY_URL'),
    'return_url' => env('VNPAY_RETURN_URL'),
    'cancel_url' => env('VNPAY_CANCEL_URL'),
];

