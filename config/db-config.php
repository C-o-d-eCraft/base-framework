<?php

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by bFrame. You're free to add / remove connections.
    |
    */

return [
    'driver' => 'mysql',
    'dsn' => 'mysql:host=mariadb;dbname=calculator;charset=utf8',

    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false
    ],
];