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
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model_name' => env('OPENAI_MODEL_NAME'),
        'base_uri' => env('OPENAI_BASE_URI'),
        'temperature' => (float) env('OPENAI_TEMPERATURE')
    ],
    'text_gen' => [
        'source_dir' => env('GEN_SOURCE_DIR'),
        'rewrite_percent' => env('GEN_REWRITE_PERCENT'),
        'ai_rewrite_days' => env('GEN_AI_REWRITE_DAYS'),
    ]
];
