<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'administrator' => [
            'users' => 'c,r,u,d',
            'consultas' => 'c,r,u,d',
            'carteira-vacina' => 'c,r,u,d',
            'postos-saude' => 'c,r,u,d',
            'medicos' => 'c,r,u,d',
            'admin' => 'c,r,u,d'
        ],
        'medico' => [
            'consultas' => 'c,r,u,d',
            'carteira-vacina' => 'c,r,u',
            'postos-saude' => 'r',
            'medicos' => 'r'
        ],
        'enfermeiro' => [
            'consultas' => 'r',
            'carteira-vacina' => 'c,r,u',
            'postos-saude' => 'r'
        ],
        'usuario' => [
            'consultas' => 'c,r,u',
            'carteira-vacina' => 'c,r',
            'postos-saude' => 'r'
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
