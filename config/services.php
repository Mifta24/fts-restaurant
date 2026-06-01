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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ai' => [
        'provider' => env('AI_PROVIDER', 'openai-compatible'),
        'base_url' => env('AI_BASE_URL', 'https://api.groq.com/openai/v1'),
        'api_key' => env('AI_API_KEY'),
        'model' => env('AI_MODEL', 'llama-3.1-8b-instant'),
        'timeout' => env('AI_TIMEOUT', 30),
        'proxy' => env('AI_PROXY'),
    ],

    'analysis' => [
        'recipient_email' => env('ANALYSIS_RECIPIENT_EMAIL', env('MAIL_FROM_ADDRESS')),
        'recipient_name' => env('ANALYSIS_RECIPIENT_NAME', 'FTS Team'),
    ],

    'whatsapp' => [
        'url' => env('WHATSAPP_URL', 'https://wa.me/'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
