<?php

namespace Panel\Config;

class Config{

    public static function rank()
    {
        return self::getConfig()['rank'];
    }
    public static function vehicle()
    {
        return self::getConfig()['vehicules'];
    }
    public static function gear()
    {
        return self::getConfig()['gear'];
    }
    public static function ConfigDatabase()
    {
        return parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'database.ini', true);
    }

    private static function getConfig()
    {
        return json_decode((file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config.json')), true);
    }
}