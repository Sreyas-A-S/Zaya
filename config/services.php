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

    'wordpress' => [
        'api_url' => env('WORDPRESS_API_URL', 'https://blog.zayawellness.com/wp-json/wp/v2'),
        'verify_ssl' => env('WORDPRESS_API_VERIFY_SSL', true),
        'username' => env('WORDPRESS_USERNAME'),
        'application_password' => env('WORDPRESS_APPLICATION_PASSWORD'),
        'cache_secret' => env('WORDPRESS_CACHE_SECRET'),
    ],

    'chatbot' => [
        'api_key' => env('CHATBOT_API_KEY'),
    ],

];
