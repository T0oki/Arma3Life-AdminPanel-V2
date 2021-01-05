<?php

namespace Panel;

use Panel\Config\Config;
use PDO;

class Database
{
    public static function server():PDO
    {
        $cfg = Config::ConfigDatabase();
        return self::createConnexion($cfg['server_database']);
    }
    public static function web():PDO
    {
        $cfg = Config::ConfigDatabase();
        return self::createConnexion($cfg['website_database']);
    }
    public static function createConnexion(array $cfg):PDO
    {
        return new PDO('mysql:host=' . $cfg['host'] . ';dbname=' . $cfg['dbname'] . ';charset=utf8', $cfg['user'], $cfg['password']);
    }
}
