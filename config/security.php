<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Segurança
    |--------------------------------------------------------------------------
    |
    | Este arquivo contém as configurações de segurança do sistema,
    | incluindo throttling, CSRF e regras de acesso.
    |
    */

    'throttling' => [
        'login_attempts' => 5,
        'login_decay_minutes' => 1,
        'password_reset_attempts' => 3,
        'password_reset_decay_minutes' => 1,
        'email_verification_attempts' => 6,
        'email_verification_decay_minutes' => 1,
    ],

    'csrf' => [
        'enabled' => true,
        'excluded_routes' => [
            'api/*',
            'webhooks/*',
        ],
    ],

    'route_access' => [
        'admin.*' => ['administrator'],
        'consultas.create' => ['medico', 'administrator'],
        'consultas.store' => ['medico', 'administrator'],
        'consultas.edit' => ['medico', 'administrator'],
        'consultas.update' => ['medico', 'administrator'],
        'consultas.destroy' => ['medico', 'administrator'],
        'carteira-vacina.create' => ['enfermeiro', 'administrator'],
        'carteira-vacina.store' => ['enfermeiro', 'administrator'],
        'carteira-vacina.edit' => ['enfermeiro', 'administrator'],
        'carteira-vacina.update' => ['enfermeiro', 'administrator'],
        'carteira-vacina.destroy' => ['enfermeiro', 'administrator'],
        'postos-saude.create' => ['administrator'],
        'postos-saude.store' => ['administrator'],
        'postos-saude.edit' => ['administrator'],
        'postos-saude.update' => ['administrator'],
        'postos-saude.destroy' => ['administrator'],
    ],

    'password_policy' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
        'max_age_days' => 90,
    ],

    'session' => [
        'timeout_minutes' => 120,
        'regenerate_on_login' => true,
        'secure_cookies' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'lax',
    ],
];

