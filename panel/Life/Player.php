<?php

namespace Panel\Life;

use Panel\Config\Config;
use Panel\Database;
use Panel\Logs;
use Panel\Permission;
use Panel\Utile;

class Player
{

    public $pid;
    private $player;
    private $home;
    private $gang;
    private $vehicle;
    private $container;

    // Variables de configurations
    private $NomGrades;
    private $VehiCFG;
    private $NomGear;

    /**
     * Player constructor.
     * @param int $playerID
     */
    public function __construct(int $playerID)
    {
        $this->NomGrades = Config::rank();
        $this->VehiCFG = Config::vehicle();
        $this->NomGear = Config::gear();

        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM players WHERE pid='$playerID'");
        $this->player = ($req) ? $req->fetch() : [];

        $req = $server_db->query("SELECT * FROM houses WHERE pid='$playerID'");
        $this->home = ($req) ? $req->fetch() : [];

        $req = $server_db->query("SELECT * FROM gangs WHERE members='%$playerID%'");
        $this->gang = ($req) ? $req->fetch() : [];

        $req = $server_db->query("SELECT * FROM vehicles WHERE pid='$playerID'");
        $this->vehicle = ($req) ? $req->fetch() : [];

        $req = $server_db->query("SELECT * FROM containers WHERE pid='$playerID'");
        $this->container = ($req) ? $req->fetch() : [];

        $this->pid = $playerID;
    }

    /**
     * @return array
     */
    public function generateProfileArray()
    {
        $player = $this->player;
        if (!$player) return ['error' => 'NoPlayer'];
        $alias = str_replace('`]"', "", str_replace('"[`', "", $player['aliases']));
        $playTime = explode(",", (str_replace(']"', "", str_replace('"[', "", $player['playtime']))));
        $playTime = $playTime[0] + $playTime[1] + $playTime[2];
        $playTime = $playTime . " Minutes";
        $cash = strrev(wordwrap(strrev($player['cash']), 3, '.', true)) . " €"; // définition de l'argent cash du joueur
        $bank = strrev(wordwrap(strrev($player['bankacc']), 3, '.', true)) . " €"; // définition de l'argent banquaire du joueur
        $coplevel = (isset($this->NomGrades['cop'][$player['coplevel']])) ? $this->NomGrades['cop'][$player['coplevel']] : $player['coplevel'];
        $mediclevel = (isset($this->NomGrades['medic'][$player['mediclevel']])) ? $this->NomGrades['medic'][$player['mediclevel']] : $player['mediclevel'];
        $adminlevel = (isset($this->NomGrades['admin'][$player['adminlevel']])) ? $this->NomGrades['admin'][$player['adminlevel']] : $player['adminlevel'];
        $license_civ = ($player['civ_licenses'] !== '"[]"') ? $this->LicensesTraitement($player['civ_licenses'], "civ") : "";
        $license_cop = ($player['cop_licenses'] !== '"[]"') ? $this->LicensesTraitement($player['cop_licenses'], "cop") : "";
        $license_med = ($player['med_licenses'] !== '"[]"') ? $this->LicensesTraitement($player['med_licenses'], "med") : "";
        $admin_drop = $this->generateDropdown('admin', $player['adminlevel']);
        $medic_drop = $this->generateDropdown('medic', $player['mediclevel']);
        $cop_drop = $this->generateDropdown('cop', $player['coplevel']);
        $inventory = $this->GetInventory($player['civ_gear']);
        $CivInvVirt = "";
        if (!empty($inventory["virtualItems"])) {
            foreach ($inventory["virtualItems"] as $Item) {
                $name = (isset($this->NomGear['virtual_item'][$Item[0]])) ? $this->NomGear['virtual_item'][$Item[0]] : $Item[0];
                $CivInvVirt = $CivInvVirt . "<tr><td><div id=\"rm_vitem_$Item[0]\" class=\"badge badge-pill badge-danger\">-</div></td><td>$name</td><td><input value=\"$Item[1]\" type=\"number\" style=\"width: 50px;\" id=\"value_vitem_$Item[0]\"></td></tr>";
            }
        }

        return [
            "player" => [
                'pid' => $player['pid'],
                'name' => $player['name'],
                'url_name' => '',
                'badges' => '',
                'alias' => $alias,
                'connect_first' => $player['insert_time'],
                'connect_last' => $player['last_seen'],
                'playtime' => $playTime,
                'cash' => $cash,
                'bank' => $bank,
                'coplevel' => $coplevel,
                'mediclevel' => $mediclevel,
                'adminlevel' => $adminlevel,
                'license_civ' => $license_civ,
                'license_cop' => $license_cop,
                'license_med' => $license_med,
                'inventory_civ_virt' => $CivInvVirt
            ],
            "edit_var" => [
                'cash' => $player['cash'],
                'bank' => $player['bankacc'],
                'cop' => $cop_drop,
                'medic' => $medic_drop,
                'admin' => $admin_drop
            ]
        ];
    }

    /**
     * @param $var
     * @param $type
     * @return string
     */
    private function LicensesTraitement($var, $type)
    { // Fonction de traitement des licenses
        $var = str_replace('`', "", $var);
        $var = str_replace('"', "", $var);
        $var = str_replace('[[', "[", $var);
        $var = str_replace(']]', "]", $var);
        $var = str_replace('],[', "|", $var);
        $var = str_replace(']', "", $var);
        $var = str_replace('[', "", $var);
        $var = str_replace("license_{$type}_", "", $var);
        $var = explode("|", $var);
        foreach ($var as $key => $value) {
            $var[$key] = explode(",", $value);
        }
        $str = "";
        foreach ($var as $value) {
            if (intval($value[1]) === 0) {
                $str = $str . <<<HTML
<button type="button" class="btn btn-danger btn-fw" id="EditLicense_{$type}_{$value[0]}">$value[0]</button>
HTML;
            } else {
                $str = $str . <<<HTML
<button type="button" class="btn btn-success btn-fw" id="EditLicense_{$type}_{$value[0]}">$value[0]</button>
HTML;
            }
        }
        return $str;
    }

    /**
     * @param string $tab
     * @param int $level
     * @return string
     */
    private function generateDropdown(string $tab, int $level)
    {
        $list = '';
        $AFDS = 0;
        foreach ($this->NomGrades[$tab] as $OneGrade) {
            $list = $list . "<option value=\"$AFDS\" {$this->IsSelected($AFDS, $level)}>$OneGrade</option>";
            $AFDS++;
        }
        return $list;
    }

    /**
     * @param $int
     * @param $CurrentLvl
     * @return string|null
     */
    private function IsSelected($int, $CurrentLvl)
    { // Fonction de séléction
        return ($int == $CurrentLvl) ? "selected" : null;
    }

    /**
     * @param string $string
     * @return array
     */
    private function GetInventory(string $string)
    { // fonction de traitement de l'inventaire
        $string = str_replace('"[', "[", $string);
        $string = str_replace(']"', "]", $string);
        $string = str_replace('`', '"', $string);

        if ($string == "[]") {
            return [];
        }

        $string = json_decode($string);

        $json = [];

        $json["uniform"] = $string[0];
        $json["vest"] = $string[1];
        $json["backpack"] = $string[2];
        $json["goggles"] = $string[3];
        $json["headgear"] = $string[4];
        $json["assignedITems"] = $string[5];
        $json["primaryWeapon"] = $string[6];
        $json["handgunWeapon"] = $string[7];
        $json["uniformItems"] = $string[8];
        $json["uniformMagazines"] = $string[9];
        $json["backpackItems"] = $string[10];
        $json["backpackMagazines"] = $string[11];
        $json["vestItems"] = $string[12];
        $json["vestMagazines"] = $string[13];
        $json["primaryWeaponItems"] = $string[14];
        $json["handgunItems"] = $string[15];
        $json["virtualItems"] = $string[16];

        return $json;
    }

    /**
     * @param int $cash
     * @param int $bank
     * @param int $cop
     * @param int $med
     * @param int $admin
     * @return string
     */
    public function edit(int $cash, int $bank, int $cop, int $med, int $admin): string
    {
        if (!Permission::hasPermission('edit_player')) return 'AccessDenied';
        $player = $this->player;
        $cash = (Permission::hasPermission('edit_player_money')) ? $cash : $player['cash'];
        $bank = (Permission::hasPermission('edit_player_money')) ? $bank : $player['bankacc'];
        $cop = (Permission::hasPermission('edit_player_cop')) ? $cop : $player['coplevel'];
        $med = (Permission::hasPermission('edit_player_med')) ? $med : $player['mediclevel'];
        $admin = (Permission::hasPermission('edit_player_admin')) ? $admin : $player['adminlevel'];
        Logs::add($_SESSION['Email'], 'editPlayer', "{$player['pid']}]-|-[{$player['cash']}]-[{$player['bankacc']}]-[{$player['coplevel']}]-[{$player['mediclevel']}]-[{$player['adminlevel']}]-|-[$cash]-[$bank]-[$cop]-[$med]-[$admin");
        $serveur_bdd = Database::server();
        $req = $serveur_bdd->prepare('UPDATE players SET cash = :cash, bankacc = :bankacc, coplevel = :coplevel, mediclevel = :mediclevel, adminlevel = :adminlevel WHERE pid = :uid');
        $req->execute([
            'cash' => $cash,
            'bankacc' => $bank,
            'coplevel' => $cop,
            'mediclevel' => $med,
            'adminlevel' => $admin,
            'uid' => $player['pid'],
        ]);
        return 'Success';
    }

    /**
     * @param string $type
     * @param string $name
     * @param int $value
     * @return string
     */
    public function license(string $type, string $name, int $value): string
    {
        $pid = $this->player['pid'];
        if (!Permission::hasPermission('edit_player_license')) return 'AccessDenied';
        $CatName = "{$type}_licenses";
        if ($value !== 0 && $value !== 1) return 'ErrorValue';
        $serveur_db = Database::server();
        $reponse = $serveur_db->query("SELECT {$CatName}, pid FROM players WHERE pid='$pid'");
        $donnees = $reponse->fetch();
        $lis_String = $donnees[$CatName];
        $find = "`license_{$type}_{$name}`,{$value}";
        $value = ($value === 0) ? 1 : 0;
        $replace = "`license_{$type}_{$name}`,{$value}";
        $lis_String = str_replace($find, $replace, $lis_String);
        Logs::add($_SESSION['Email'], 'editLisence', $value . "]-[license_" . $type . "_" . $name . "]-[" . $pid);
        $serveur_db->Query("UPDATE players SET {$CatName}='$lis_String' WHERE pid='$pid'");
        return "Success";
    }

    /**
     * @return string
     */
    public function urlPlayer()
    {
        $player = $this->player;
        return "<a href='" . Utile::getRoute('profile') . $player['pid'] . "'>{$player['name']}</a>";
    }

    /**
     * @param int $amount
     * @param string $msg
     * @param string|null $evidence
     * @return string
     */
    public function refund(int $amount, string $msg, string $evidence = null)
    {
        if (!Permission::hasPermission('staff_refund')) return 'AccessDenied';
        $adminid = $_SESSION['UID'];
        $webdb = Database::web();
        $req = $webdb->prepare("INSERT INTO Refund(date, owner, target, raison, amount, url) VALUES(:date, :owner, :target, :raison, :amount, :url)");
        $req->execute([
            'date' => date("Y-m-d H:i:s"),
            'owner' => htmlspecialchars($adminid),
            'target' => htmlspecialchars(($this->pid)),
            'raison' => htmlspecialchars($msg),
            'amount' => htmlspecialchars($amount),
            'url' => htmlspecialchars($evidence),
        ]);
        $serverdb = Database::server();
        $new_bank = (((int)$this->player['bankacc']) + $amount);
        $serverdb->query("UPDATE players SET bankacc='$new_bank' WHERE pid='" . $this->pid . "'");
        return "Success";
    }
}