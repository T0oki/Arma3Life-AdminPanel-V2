<?php

namespace Panel\Life;

use Panel\Database;
use Panel\Permission;

class Vehicle
{
    private $vehicle;
    public $exist = false;

    public function __construct(int $vehicleID)
    {
        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM vehicles WHERE id='$vehicleID'");
        if ($req){
            $this->vehicle = $req->fetch();
            $this->exist = true;
        }
    }
    public function delete():string
    {
        if(!Permission::hasPermission('edit_vehicle_delete')) return "AccessDenied";
        $server_db = Database::server();
        $server_db->query("DELETE FROM vehicles WHERE id='{$this->vehicle['id']}'");
        return "Success";
    }
}