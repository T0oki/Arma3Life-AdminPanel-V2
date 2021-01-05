<?php

namespace Panel\Request;

use Panel\Database;
use Panel\Logs;

class Connect
{
    private static $email;
    private static $password;

    /**
     * @param string $email
     * @param string $password
     */
    public static function init(string $email, string $password)
    {
        self::$email = htmlspecialchars(strtolower($email));
        self::$password = htmlspecialchars($password);
    }

    /**
     * @return string
     */
    public static function connexion(): string
    {
        Logs::add(self::$email, 'login', 'try');
        $web_db = Database::web();
        $req = $web_db->query("SELECT * FROM Utilisateurs WHERE Email='" . self::$email . "'");
        $result = $req->fetch();
        if (!$result) return self::errorLogin('DENIED'); // L'utilisateur n'existe pas
        if ($result['Email'] !== self::$email) return self::errorLogin('DENIED'); // L'utilisateur n'existe pas
        $true_password = $result['Password'];
        if (!password_verify(self::$password, $true_password)) return self::errorLogin('DENIED'); // Mot de passe Incorrecte
        $_SESSION['UID'] = $result['UID'];
        $_SESSION['Nom'] = $result['Nom'];
        $_SESSION['SafeCode'] = $result['SafeCode'];
        $_SESSION['avatar'] = $result['avatar'];
        if (!$result['activate']) return self::errorLogin('DENIED_DISABLE'); // Compte désactivé
        $_SESSION['Statut'] = true;
        return self::logged(); // connexion du client
    }

    /**
     * @param string $message
     * @return string
     */
    private static function errorLogin(string $message): string
    {
        Logs::add(self::$email, 'login', $message);
        return $message;
    }

    /**
     * @return string
     */
    private static function logged(): string
    {
        $web_db = Database::web();
        $web_db->query("UPDATE Utilisateurs SET LastActivity='" . time() . "' WHERE UID='" . $_SESSION['UID'] . "' AND SafeCode='" . $_SESSION['SafeCode'] . "'");
        return 'Logged';
    }
}