<?php
namespace Panel;

class Utile {
    private static $route;
    public static function init($route)
    {
        self::$route = $route;
    }
    public static function generateError($msg){
        return "<script>window.onload = function() {
          errorRequestToast(); console.log('$msg');
        }</script>";
    }
    public static function getRoute($route){
        return self::$route->pathFor($route);
    }
    public static function nameByRank(int $rankid):string
    {
        $web_db = Database::web();
        $req = $web_db->query("SELECT name FROM `Rank` WHERE id='$rankid'");
        if($req){
            $rep = $req->fetch();
            return $rep['name'];
        } else return 'error';
    }
}