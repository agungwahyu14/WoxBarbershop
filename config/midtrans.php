<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway integration
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    // Merchant configuration
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    
    // URLs
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', '/api/midtrans/notification'),
    'finish_url' => env('MIDTRANS_FINISH_URL', '/payment/finish'),
    'error_url' => env('MIDTRANS_ERROR_URL', '/payment/error'),
    'unfinish_url' => env('MIDTRANS_UNFINISH_URL', '/payment/unfinish'),
    
    // Payment expiry (in hours)
    'payment_expiry' => env('MIDTRANS_PAYMENT_EXPIRY', 24),
    
    // Enabled payment methods
    'enabled_payments' => [
        'credit_card',
        'bca_va',
        'bni_va', 
        'bri_va',
        'mandiri_va',
        'permata_va',
        'other_va',
        'gopay',
        'shopeepay',
        'dana',
        'ovo',
        'linkaja',
        'qris',
        'indomaret',
        'alfamart'
    ],
    
    // Transaction limits
    'min_amount' => 10000, // Rp 10,000
    'max_amount' => 10000000, // Rp 10,000,000
    
    // Credit card configuration
    'credit_card' => [
        'secure' => true,
        'bank' => 'bca', // acquiring bank
        'installment' => [
            'required' => false,
            'terms' => [
                'bni' => [3, 6, 12],
                'mandiri' => [3, 6, 12],
                'cimb' => [3],
                'bca' => [3, 6, 12],
                'offline' => [6, 12]
            ]
        ],
        'whitelist_bins' => [
            // "48111111",
            // "41111111"
        ]
    ],
    
    // Logging configuration
    'log_notifications' => env('MIDTRANS_LOG_NOTIFICATIONS', true),
    
    // Security
    'verify_signature' => true,
    'allowed_ips' => [
        '103.208.23.0/24',
        '103.208.23.6',
        '103.127.16.0/24',
        '103.127.17.6'
    ]
];