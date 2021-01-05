<?php

namespace Panel;

class Logs
{
    public static function add (string $User, string $Type, string $Content = null) {
        $web_db = Database::web();

        $req = $web_db->prepare("INSERT INTO Logs(Type, User, Time, Content) VALUES(:Type, :User, :Time, :Content)");
        $req->execute(array(
            'Type' => $Type,
            'User' => ((empty($User)) ? $_SESSION['Email'] : $User),
            'Time' => date("Y-m-d H:i:s"),
            'Content' => $Content,
        ));
    }
}