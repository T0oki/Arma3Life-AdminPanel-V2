<?php

namespace Panel;

class Permission
{
    private static $permissions;

    public static function init(int $rank)
    {
        $web_db = Database::web();
        $permissions = $web_db->query("SELECT * FROM `Rank` WHERE id='$rank'");
        if (!$permissions) self::$permissions = false;
        else self::$permissions = $permissions->fetch();
    }

    public static function getArray():array
    {
        $perms = self::$permissions;
        if (!$perms) return [false];

        return [
            "name" => $perms['name'],

            "A" => $perms['isAdmin'],
            "view" => [
                "players"   => $perms['view_player'],
                "vehicle"   => $perms['view_vehicle'],
                "home"      => $perms['view_home'],
                "gang"      => $perms['view_gang'],
                "container" => $perms['view_container']
            ],
            "edit" => [
                "players"   => [
                    "edit"      => $perms['edit_player'],
                    "license"   => $perms['edit_player_license'],
                    "money"     => $perms['edit_player_money'],
                    "cop"       => $perms['edit_player_cop'],
                    "medic"     => $perms['edit_player_med'],
                    "admin"     => $perms['edit_player_admin'],
                    "delete"    => $perms['edit_player_delete']
                ],
                "vehicle"   => [
                    'edit'      => $perms['edit_vehicle'],
                    'delete'    => $perms['edit_vehicle_delete']
                ],
                "home"      => [
                    'edit'      => $perms['edit_home'],
                    'delete'    => $perms['edit_home_delete']
                ],
                "gang"      => [
                    'edit'      => $perms['edit_gang'],
                    'members'   => $perms['edit_gang_members']
                ],
                "container" => $perms['edit_container']
            ],
            "staff" => [
                "refund"    => $perms['staff_refund'],
                "mange"     => $perms['staff_manage'],
                "logs"      => $perms['staff_logs']
            ]
        ];
    }

    public static function hasPermission(string $perm):bool
    {

        $web_db = Database::web();
        $rank = $_SESSION['rank'];
        $req = $web_db->query("SELECT $perm FROM `Rank` WHERE id='$rank'");
        if (!$req) return false;
        $res = $req->fetch();
        if($res[$perm]) return true;
        else return false;
    }
}