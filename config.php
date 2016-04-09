<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 08.03.2016
 * Time: 21:52
 */

define("ROOT", __DIR__ . "/");

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Config
{

    private static $config = [
        'mysql'  => [
            'db_name' => 'twa-zad4',
            'user'    => 'root',
            'pass'    => '',
            'charset' => 'utf8',
        ],
        'google' => [
            'client_id'     => '997320583883-qm9or069sjdo0sfbj917lirvdpsg14qp.apps.googleusercontent.com',
            'client_secret' => 'F39uRKDbsISibZbCQG1JailT',
            'redirect_uri'  => 'http://147.175.99.99.nip.io/zad33/oauth2.php'
        ],
        'app'    => [
            'base_url'      => 'http://twa-zad4',
            'dir_view'      => 'views',
            'password_hash' => false
        ]
    ];

    public static function get($key, $default = null)
    {
        list($first, $second) = explode('.', $key);

        if (isset(self::$config[$first][$second])) {
            return self::$config[$first][$second];
        }

        return $default;
    }
}