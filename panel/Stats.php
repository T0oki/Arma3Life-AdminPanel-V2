<?php
namespace Panel;

class Stats {
    public static function homeStat():array
    {
        $server_db = Database::server();
        $array['players'] = $server_db->query("SELECT COUNT(*) FROM `players`;")->fetch()[0];
        $array['vehicles'] = $server_db->query("SELECT COUNT(*) FROM `vehicles`;")->fetch()[0];
        $array['houses'] = $server_db->query("SELECT COUNT(*) FROM `houses`;")->fetch()[0];
        $cash = $server_db->query("SELECT SUM(cash) FROM `players`;")->fetch()[0];
        $bankacc = $server_db->query("SELECT SUM(bankacc) FROM `players`;")->fetch()[0];
        $array['money'] = (int) ($cash + $bankacc);
        return $array;
    }
}