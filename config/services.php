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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8000/auth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', 'http://127.0.0.1:8001/auth/facebook/callback'),
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI', 'http://127.0.0.1:8001/auth/apple/callback'),
        'team_id' => env('APPLE_TEAM_ID'),
        'key_id' => env('APPLE_KEY_ID'),
        'private_key' => env('APPLE_PRIVATE_KEY'),
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

    'razorpay' => [
        'key' => env('RAZORPAY_KEY_ID'),
        'secret' => env('RAZORPAY_KEY_SECRET'),
        'verify_ssl' => env('RAZORPAY_VERIFY_SSL', env('APP_ENV') === 'local' ? false : true),
    ],

    'agora' => [
        'app_id' => env('AGORA_APP_ID'),
        'app_certificate' => env('AGORA_APP_CERTIFICATE'),
    ],

    'jaas' => [
        'domain' => env('JAAS_DOMAIN', env('JAAAS_DOMAIN', '8x8.vc')),
        'app_id' => env('JAAS_APP_ID', env('JAAAS_APP_ID')),
        'kid' => env('JAAS_API_KEY_ID', env('JAAAS_API_KEY_ID')),
        'private_key' => env('JAAS_PRIVATE_KEY', env('JAAAS_PRIVATE_KEY')),
        'private_key_path' => env('JAAS_PRIVATE_KEY_PATH', env('JAAAS_PRIVATE_KEY_PATH')),
    ],

    'jitsi' => [
        'domain' => env('JITSI_DOMAIN', 'meet.jit.si'),
    ],

    'daily' => [
        'api_key' => env('DAILY_API_KEY'),
        'domain' => env('DAILY_DOMAIN', 'zaya.daily.co'), // Replace with your default or env
    ],

    'zego' => [
        'app_id' => env('ZEGO_APP_ID'),
        'server_secret' => env('ZEGO_SERVER_SECRET'),
        'cloud_recording' => [
            'api_base' => env('ZEGO_CLOUD_RECORDING_API_BASE', 'https://cloudrecord-api.zego.im/'),
            'storage_vendor' => env('ZEGO_CLOUD_RECORDING_STORAGE_VENDOR', 1),
            'output_folder' => env('ZEGO_CLOUD_RECORDING_OUTPUT_FOLDER', 'zego-recordings'),
            'max_idle_time' => env('ZEGO_CLOUD_RECORDING_MAX_IDLE_TIME', 30),
            'max_record_time' => env('ZEGO_CLOUD_RECORDING_MAX_RECORD_TIME', 7200),
        ],
    ],

    'google_drive' => [
        'service_account_email' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL'),
        'service_account_private_key' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_PRIVATE_KEY'),
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
        'make_public' => env('GOOGLE_DRIVE_MAKE_PUBLIC', true),
    ],

    'google_meet' => [
        'master_account' => env('GOOGLE_MEET_MASTER_ACCOUNT_EMAIL'),
        'service_account_json' => env('GOOGLE_MEET_SERVICE_ACCOUNT_JSON_PATH'),
    ],

    'scheduler' => [
        'token' => env('SCHEDULER_TOKEN', 'zaya-secret-scheduler-key-2024'),
    ],

];
