<?php
namespace Panel;

use Panel\Config\Config;
use Panel\Database;
use Panel\Life\Player;
use Panel\Permission;
use Panel\Utile;

class Liste {

    public static function player():string
    {
        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM players");
        if(!$req) return Utile::generateError('NoPlayer');
        $NomGrades = Config::rank();
        $head = "<tr>
    <th>ID</th>
    <th>Nom RP</th>
    <th>Player ID</th>
    <th>Argent Cash</th>
    <th>Compte Bancaire</th>
    <th hidden></th>
    <th>Cop Level</th>
    <th hidden></th>
    <th>Medic Level</th>
    <th hidden></th>    
    <th>Admin Level</th>
    <th>Profile</th>
    ".((Permission::hasPermission('edit_player')) ? "<th>Éditions Rapides</th>" : "")."
</tr>";
        $body = '';
        while($result = $req->fetch()){
            $cash = strrev(wordwrap(strrev($result['cash']), 3, '.', true))." €";
            $bank = strrev(wordwrap(strrev($result['bankacc']), 3, '.', true))." €";
            $copLvl = (isset($NomGrades['cop'][$result['coplevel']])) ? $NomGrades['cop'][$result['coplevel']] : $result['coplevel'];
            $medLvl = (isset($NomGrades['medic'][$result['mediclevel']])) ? $NomGrades['medic'][$result['mediclevel']] : $result['mediclevel'];
            $adminLvl = (isset($NomGrades['admin'][$result['adminlevel']])) ? $NomGrades['admin'][$result['adminlevel']] : $result['adminlevel'];
            $link = Utile::getRoute('profile');
            $body .= <<<HTML
<tr>
    <td>{$result['uid']}</td>
    <td>{$result['name']}</td>
    <td>{$result['pid']}</td>
    <td>{$cash}</td>
    <td>{$bank}</td>
    <td hidden>{$result['coplevel']}</td>
    <td>{$copLvl}</td>
    <td hidden>{$result['mediclevel']}</td>
    <td>{$medLvl}</td>
    <td hidden>{$result['adminlevel']}</td>
    <td>{$adminLvl}</td>
    <td><button class="btn btn-outline-primary" onclick="window.location.href='{$link}{$result['pid']}'">Ouvrir</button></td>
    <td><button type="button" class="btn btn-outline-success btnModifier" data-toggle="modal" data-target="#editionrapide">Editer</button></td>
</tr>
HTML;

        }
        return <<<HTML
    <thead>$head</thead>
    <tbody>$body</tbody>
HTML;

    }

    public static function vehicle($pid = null):string
    {
        $VehiCFG = Config::vehicle();
        $player = (!$pid) ? false : true;
        $server_db = Database::server();
        if($player) {
            $req = $server_db->query("SELECT * FROM vehicles WHERE pid='$pid'");
        } else {
            $req = $server_db->query("SELECT * FROM vehicles");
        }
        if(!$req) return(Utile::generateError('NoVehicle'));
        $head = "<tr>
                    <th></th>
                    <th>ID</th>
                    ".((!$player) ? "<th>Propriétaire</th>" : "")."
                    <th>Classname</th>
                    <th>Plate</th>
                    <th>Immatriculation</th>
                    <th>Inventaire Virtuel</th>
                    <th>Inventaire Physique</th>
                    ".((Permission::hasPermission('edit_vehicle')) ? "<th>Actions</th>" : "")."
                </tr>";
        $body = '';
        while ($result = $req->fetch())
        {
            switch ($result['type']) {
                case 'Car':
                    $result['type'] = "<i class=\"mdi mdi-car\"></i>";
                    break;
                case 'Air':
                    $result['type'] = "<i class=\"mdi mdi-airplane\"></i>";
                    break;
                case 'Ship':
                    $result['type'] = "<i class=\"mdi mdi-sailing\"></i>";
                    break;
            }
            if (!$player){
                $player_r = new Player($result['pid']);
            }
            $classname = $VehiCFG['classname'][$result['classname']] ?? $result['classname'];
            $body.= "<tr>
    <td>{$result['type']}</td>
    <td>{$result['id']}</td>
    ".((!$player) ? "<td>{$player_r->urlPlayer()}</td>" : "")."
    <td>{$classname}</td>
    <td>Plate</td>
    <td>Immatriculation</td>
    <td>
        <button type=\"button\" class=\"btn btn-outline-info\" data-toggle=\"modal\" data-target=\"#openpitemvoiture\">Ouvrir</button>
    </td>
    <td>
        <button type=\"button\" class=\"btn btn-outline-info\" data-toggle=\"modal\" data-target=\"#openvitemvoiture\">Ouvrir</button>
    </td>
    ".((Permission::hasPermission('edit_vehicle_delete')) ? "<td><button id=\"DeleteVehicle{$result['id']}\" type=\"button\" class=\"btn btn-outline-danger\">Supprimer</button></td>" : "")."
</tr>";

        }
        return self::FormatTable($head, $body);

    }

    public static function house($pid = null):string
    {
        $player = (!$pid) ? false : true;
        $server_db = Database::server();
        if($player) {
            $req = $server_db->query("SELECT * FROM houses WHERE pid='$pid'");
        } else {
            $req = $server_db->query("SELECT * FROM houses");
        }
        if(!$req) return(Utile::generateError('NoHouse'));
        $head = "<tr>
                    <th></th>
                    <th>ID</th>
                    ".((!$player) ? "<th>Propriétaire</th>" : "")."
                    <th>Type</th>
                    <th>Position</th>
                    <th>Date d'aquisition</th>
                    ".((Permission::hasPermission('edit_vehicle')) ? "<th>Actions</th>" : "")."
                </tr>";
        $body = '';
        while ($result = $req->fetch())
        {
            $icon = ($result['garage']) ? "<i class=\"mdi mdi-garage-open\"></i>" : "<i class=\"mdi mdi-home\"></i>";
            $type = $result['garage'] ? 'Garage' : 'Maison';
            if (!$player){
                $player_r = new Player($result['pid']);
            }
            $body.= "<tr>
    <td>{$icon}</td>
    <td>{$result['id']}</td>
    ".((!$player) ? "<td>{$player_r->urlPlayer()}</td>" : "")."
    <td>{$type}</td>
    <td>{$result['pos']}</td>
    <td>{$result['owned']}</td>
    ".((Permission::hasPermission('edit_home_delete')) ? "<td><button id=\"DeleteHome{$result['id']}\" type=\"button\" class=\"btn btn-outline-danger\">Supprimer</button></td>" : "")."
</tr>";

        }
        return self::FormatTable($head, $body);

    }

    public static function container($pid = null):string
    {
        $player = (!$pid) ? false : true;
        $server_db = Database::server();
        if($player) {
            $req = $server_db->query("SELECT * FROM containers WHERE pid='$pid'");
        } else {
            $req = $server_db->query("SELECT * FROM containers");
        }
        if(!$req) return(Utile::generateError('NoContainer'));
        $head = "<tr>
                    <th>ID</th>
                    ".((!$player) ? "<th>Propriétaire</th>" : "")."
                    <th>classname</th>
                    <th>Position</th>
                    <th>Date d'aquisition</th>
                    ".((Permission::hasPermission('edit_container')) ? "<th>Actions</th>" : "")."
                </tr>";
        $body = '';
        while ($result = $req->fetch())
        {
            if (!$player){
                $player_r = new Player($result['pid']);
            }
            $body.= "<tr>
    <td>{$result['id']}</td>
    ".((!$player) ? "<td>{$player_r->urlPlayer()}</td>" : "")."
    <td>{$result['classname']}</td>
    <td>{$result['pos']}</td>
    <td>{$result['owned']}</td>
    ".((Permission::hasPermission('edit_container_delete')) ? "<td><button id=\"DeleteContainer{$result['id']}\" type=\"button\" class=\"btn btn-outline-danger\">Supprimer</button></td>" : "")."
</tr>";

        }
        return self::FormatTable($head, $body);

    }
    public static function gang():string
    {
        $server_db = Database::server();
        $req = $server_db->query("SELECT * FROM gangs");
        if(!$req) return(Utile::generateError('NoGang'));
        $head = "<tr>
                    <th>ID</th>
                    <th>Propriétaire</th>
                    <th>Nom</th>
                    <th>Membres</th>
                    <th>Max Membres</th>
                    <th>Compte bancaire</th>
                    <th>Création</th>
                    ".((Permission::hasPermission('edit_gang')) ? "<th>Actions</th>" : "")."
                </tr>";
        $body = '';
        while ($result = $req->fetch())
        {
            $owner = new Player($result['owner']);
            $body.= "<tr>
                        <td>{$result['id']}</td>
                        <td>{$owner->urlPlayer()}</td>
                        <td>{$result['name']}</td>
                        <td><button type=\"button\" class=\"btn btn-outline-info showMembre\" data-toggle=\"modal\" data-target=\"#listemembre\">Liste Membre</button></td>
                        <td>{$result['maxmembers']}</td>
                        <td>{$result['bank']}</td>
                        <td>{$result['insert_time']}</td>
                        ".((Permission::hasPermission('edit_gang_delete')) ? "<td><button id=\"DeleteGang{$result['id']}\" type=\"button\" class=\"btn btn-outline-danger\">Supprimer</button></td>" : "")."
                    </tr>";

        }
        return self::FormatTable($head, $body);

    }

    public static function refund($pid = null):string
    {
        $player = (!$pid) ? false : true;
        $web_db = Database::web();
        if($player) {
            $req = $web_db->query("SELECT * FROM Refund WHERE target='$pid'");

        } else {
            $req = $web_db->query("SELECT * FROM Refund");
        }
        if(!$req) return Utile::generateError('NoRefund');
        $head = "<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Émetteur</th>
    ".((!$player) ? "<th>Joueur</th>" : "")."
    <th>Montant</th>
    <th>Raison</th>
</tr>";
        $body = '';
        while($result = $req->fetch()){
            $emmeteur = new Player($result['owner']);
            $target = new Player($result['target']);
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $result['url']);
            $cash = strrev(wordwrap(strrev($result['amount']), 3, '.', true))." €";
            $body.= "<tr>
    <td>{$result['id']}</td>
    <td>{$result['date']}</td>
    <td>{$emmeteur->urlPlayer()}</td>
    ".((!$player) ? "<td>{$target->urlPlayer()}</td>" : '')."
    <td>{$cash}</td>
    <td id=\"M_raison{$result['id']}\" hidden>{$result['raison']}</td>
    <td id=\"M_evidence{$result['id']}\" hidden>{$string}</td>
    <td><button type=\"button\" class=\"btn btn-outline-primary\" data-toggle=\"modal\" data-target=\"#descRemboursement\" id=\"refundDescButton{$result['id']}\">Ouvrir </button></td>
</tr>";

        }
        return self::FormatTable($head, $body);
    }

    public static function staff()
    {
        $web_db = Database::web();
        $req = $web_db->query("SELECT * FROM Utilisateurs WHERE `rank` > 0");
        if(!$req) return Utile::generateError('NoStaff');
        $header = <<<HTML
<tr>
    <th>Nom</th>
    <th>PID</th>
    <th>Profile</th>
    <th>Groupe</th>
    <th>Editions</th>
</tr>
HTML;
        $body = '';
        $link = Utile::getRoute('profile');
        while($result = $req->fetch())
        {
            $rankname = Utile::nameByRank($result['rank']);
            $body.= <<<HTML
<tr>
    <td>{$result['Nom']}</td>
    <td>{$result['UID']}</td>
    <td><button type="button" onclick="location.href='{$link}{$result['UID']}'" class="btn btn-outline-primary">Ouvrir</button></td>
    <td><div class="badge badge-outline-warning">$rankname</div></td>
    <td><button type="button" class="btn btn-outline-success">Modifier</button></td>
</tr>
HTML;
        }
        return self::FormatTable($header, $body);

    }

    public static function dropGroupFastEdit():array
    {
        $cfg_rank = Config::rank();
        $list = ["cop" => "", "medic" => "", "admin" => ""];
        $tab = ['cop', 'medic', 'admin'];
        foreach ($tab as $onetab)
        {
            $AFDS = 0;
            foreach ($cfg_rank[$onetab] as $OneGrade) {
                $list[$onetab] .= "<option value=\"$AFDS\">$OneGrade</option>";
                $AFDS++;
            }
        }
        return $list;
    }

    private static function FormatTable(string $head, string $body):string
    {
        return <<<HTML
<thead>
    $head
</thead>
<tbody>
    $body
</tbody>
HTML;
    }
}