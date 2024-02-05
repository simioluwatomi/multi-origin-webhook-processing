<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'lemon-squeezy' => [
        'secret_key' => env('LEMONSQUEEZY_SECRET_KEY',),
        'webhook' => [
            'secret_key' => env('LEMONSQUEEZY_WEBHOOK_SECRET_KEY'),
            'events' => explode(',', env('LEMONSQUEEZY_WEBHOOK_EVENTS', sprintf(
                '%s',
                'order_created,order_refunded,subscription_created,subscription_updated',
            ))),
        ]
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'paystack' => [
        'secret_key' => env('PAYSTACK_SECRET_KEY',),
        'public_key' => env('PAYSTACK_PUBLIC_KEY',),
        'webhook' => [
            'source_ips' => ['52.31.139.75', '52.49.173.169', '52.214.14.220'],
            'events' => explode(',', env('PAYSTACK_WEBHOOK_EVENTS', sprintf(
                '%s',
                'charge.success,transfer.failed,transfer.success,transfer.reversed',
            ))),
        ]
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
