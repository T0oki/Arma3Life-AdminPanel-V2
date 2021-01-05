<?php

namespace Panel\Life;

use Panel\Database;
use Panel\Permission;

class Gang
{
    private $gang;
    public $exist = false;

    public function __construct(int $gangID)
    {
        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM gangs WHERE id='$gangID'");
        if ($req) {
            $this->gang = $req->fetch();
            $this->exist = true;
        }
    }
    public function memberList():string
    {
        $list = "";
        foreach ($this->membersForTab() as $oneMember) {
            $ifHasPerm = (Permission::hasPermission('edit_gang_members')) ? "<td><button type=\"button\" class=\"btn btn-outline-danger RetirerMembre\">Retirer du Gang</button></td>" : "" ;
            $list .= "<tr><td>$oneMember</td>$ifHasPerm</tr>";
        }
        return $list;
    }
    private function membersForTab()
    {
        $membres = $this->gang['members'];
        $membres = str_replace("\"[`", '', (str_replace("`]\"", '', $membres)));
        $membres = explode("`,`", $membres);
        $membersArray = [];
        foreach ($membres as $OneMember){
            $player = new Player((int)$OneMember);
            array_push($membersArray, $player->urlPlayer());
        }
        return $membersArray;
    }
    /*public function delete():string
    {
        if(!Permission::hasPermission('edit_home_delete')) return "AccessDenied";
        $server_db = Database::server();
        $server_db->query("DELETE FROM houses WHERE id='{$this->home['id']}'");
        return "Success";
    }*/
}