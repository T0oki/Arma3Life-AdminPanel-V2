<?php

namespace Panel\Life;

use Panel\Database;
use Panel\Permission;

class Home
{
    private $home;
    public $exist = false;

    public function __construct(int $homeID)
    {
        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM houses WHERE id='$homeID'");
        if($req) {
            $this->home = $req->fetch();
            $this->exist = true;
        }
    }
    public function delete():string
    {
        if(!Permission::hasPermission('edit_home_delete')) return "AccessDenied";
        $server_db = Database::server();
        $server_db->query("DELETE FROM houses WHERE id='{$this->home['id']}'");
        return "Success";
    }
}