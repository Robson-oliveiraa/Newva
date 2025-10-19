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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'demas' => [
        'base_url' => env('DEMAS_API_URL', 'https://apidadosabertos.saude.gov.br/'),
        'api_token' => env('DEMAS_API_TOKEN'),
        'timeout' => env('DEMAS_API_TIMEOUT', 30),
        'cache_ttl' => env('DEMAS_CACHE_TTL', 3600), // 1 hora em segundos
        'municipio_ibge' => env('DEMAS_MUNICIPIO_IBGE', '1100205'), // Porto Velho - RO
        'estado_uf' => env('DEMAS_ESTADO_UF', 'RO'), // Rondônia
    ],

];
