<?php

return [
    'run_env' => env('CS_RUN_ENV', 'apitest.cybersource.com'),
    'merchant_id' => env('CS_MERCHANT_ID', 'testrest'),
    'api_key_id' => env('CS_KEY_ID', '08c94330-f618-42a3-b09d-e1e43be5efda'),
    'secret_key' => env('CS_SECRET_KEY', 'SOMEFAKEKEY'),
    'test' => [
        'payment' => [
            'decline' => env('CS_TEST_PAYMENT_DECLINE', false),
            'invalid_data' => env('CS_TEST_PAYMENT_INVALID_DATA', false),
        ],
        'refund' => [
            'invalid_data' => env('CS_TEST_REFUND_INVALID_DATA', false),
        ],
    ],
];
