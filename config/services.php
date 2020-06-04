<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    'wx_xcx_kuandai' => [ //宽带-小程序
        'appid' => 'wx6466f6eb415831a6',
        'secret' => '6cc579b4527fed20121ce42aed545695'
    ],
    'wx_xcx' => [ //道智乐-小程序
        'appid' => 'wx547272faad546cb2',
        'secret' => '40e214baaf24245c3cd5a2d61b527460'
    ],
    'wx_dzl' => [ //道智乐-公众号
        'appid' => 'wxd1a2bff629a11b93',
        'secret' => 'cc257c5ece1a37d792d7b72a5c0a5a9f'
    ],
    'wx_wsl' => [ //往生路-公众号
        'appid' => 'wx8942ca06eb04e5a1',
        'secret' => '352b09f8deab16ddd67c9100d640ccc8'
    ]

];
