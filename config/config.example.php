<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the levels of validation that should be performed on the
    | current environment. The available options are: "example" | "current".
    |
     */
    'config' => [
        'validate' => ['example', 'current'],
        'throw'  => true, // If set to true, an exception will be thrown if validation fails
    ],

    /*
    |--------------------------------------------------------------------------
    | Schema
    |--------------------------------------------------------------------------
    |
    | Define the database schema for each environment. The available options are:
    | "local" or "production". You can define the schema using laravel's
    | built-in Rule class and validation methods.
    |
     */

    'schema' => [
        'local' => [
            'APP_NAME'          => ['required', 'string'],
            'APP_ENV'           => ['required', 'string'],
            'APP_KEY'           => ['required', 'string'],
            'APP_DEBUG'         => ['required', 'boolean'],
            'APP_URL'           => ['required', 'url'],
            'LOG_CHANNEL'       => ['required', 'string'],
            'DB_CONNECTION'     => ['required', 'string'],
            'DB_HOST'           => ['required', 'string'],
            'DB_PORT'           => ['required', 'integer'],
            'DB_DATABASE'       => ['required', 'string'],
            'DB_USERNAME'       => ['required', 'string'],
            'DB_PASSWORD'       => ['required', 'string'],
            'BROADCAST_DRIVER'  => ['required', 'string'],
            'CACHE_DRIVER'      => ['required', 'string'],
            'QUEUE_CONNECTION'  => ['required', 'string'],
            'SESSION_DRIVER'    => ['required', 'string'],
            'SESSION_LIFETIME'  => ['required', 'integer'],
            'REDIS_HOST'        => ['required', 'string'],
            'REDIS_PASSWORD'    => ['required', 'string'],
            'REDIS_PORT'        => ['required', 'integer'],
            'MAIL_MAILER'       => ['required', 'string'],
            'MAIL_HOST'         => ['required', 'string'],
            'MAIL_PORT'         => ['required', 'integer'],
            'MAIL_USERNAME'     => ['required', 'string'],
            'MAIL_PASSWORD'     => ['required', 'string'],
            'MAIL_ENCRYPTION'   => ['required', 'string'],
            'MAIL_FROM_ADDRESS' => ['required', 'email'],
            'MAIL_FROM_NAME'    => ['required', 'string'],

        ],
        'production' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment schema's
    |--------------------------------------------------------------------------
    |
    | Define the database schema for each environment. The available options are:
    | "development" | "production". You can define the schema using laravel's
    | built-in Rule class and validation methods.
    |
     */
    'environments' => [
        'development' => [
            'APP_NAME'  => ['required', 'string'],
            'APP_ENV'   => ['required', 'string'],
            'APP_KEY'   => ['required', 'string'],
            'APP_DEBUG' => ['required', 'boolean'],
            'APP_URL'   => ['required', 'url'],

        ],
        'production' => [
            'APP_NAME'  => ['required', 'string'],
            'APP_ENV'   => ['required', 'string'],
            'APP_KEY'   => ['required', 'string'],
            'APP_DEBUG' => ['required', 'boolean'],
            'APP_URL'   => ['required', 'url'],
        ],
    ],

];
