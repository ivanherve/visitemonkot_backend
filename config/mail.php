<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 23-03-19
 * Time: 10:01
 */

// You should first install guzzle and illuminate/mail with composer
// These are the commands :
// composer require illuminate/mail
// composer require guzzlehttp/guzzle
return [
    'driver' => env('MAIL_DRIVER'),
    'host' => env('MAIL_HOST'),
    'port' => env('MAIL_PORT'),
    'from' => [
        'address' => 'contact@visitemonkot.be',
        'name' => 'VisiteMonKot.be'
    ],
    'encryption' => env('MAIL_ENCRYPTION'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD')
];