<?php
namespace App\Middlewares;

use Panel\Database;
use Panel\Permission;
use Slim\Http\Request;
use Slim\Http\Response;
use Twig\Environment;

class ActualiseMiddleware {
    private $router;
    private $twig;

    /**
     * ActualiseMiddleware constructor.
     * @param $router
     * @param Environment $twig
     */
    public function __construct($router, Environment $twig)
    {

        $this->router = $router;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next)
    {

        if (!isset($_SESSION['Statut'])) return self::logout($response);
        $web_db = Database::web();
        $req = $web_db->query("SELECT * FROM Utilisateurs WHERE UID='".$_SESSION['UID']."' AND SafeCode='".$_SESSION['SafeCode']."'");
        if(!$req) return self::logout($response);
        $result = $req->fetch();
        if(!$result['activate']) return self::logout($response);
        $_SESSION['Statut'] = true;
        $_SESSION['Email'] = $result['Email'];
        $_SESSION['Nom'] = $result['Nom'];
        $_SESSION['avatar'] = $result['avatar'];
        $_SESSION['rank'] = $result['rank'];
        Permission::init($result['rank']);
        $this->twig->addGlobal('permission', Permission::getArray());

        $new_SafeCode = self::CreateSafeCode(70);
        $web_db->query("UPDATE Utilisateurs SET SafeCode='$new_SafeCode', IP='".self::get_ip()."' WHERE UID='".$_SESSION['UID']."' AND SafeCode='".$_SESSION['SafeCode']."'");
        $_SESSION['SafeCode'] = $new_SafeCode;

        $LastActivity = $result['LastActivity'];
        $Now = time();
        $TimeDif = $Now - $LastActivity;
        if ($TimeDif >= 15*60) return self::logout($response);
        $web_db->query("UPDATE Utilisateurs SET LastActivity='$Now' WHERE UID='".$_SESSION['UID']."' AND SafeCode='".$new_SafeCode."'");
        return $next($request, $response);

    }
    private function CreateSafeCode($longueur = 10)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longueurMax = strlen($caracteres);
        $chaineAleatoire = '';
        for ($i = 0; $i < $longueur; $i++)
        {
            $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
        }
        return $chaineAleatoire;
    }
    private function get_ip() {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        }
    }
    private function logout($response) {
        return $response = $response->withRedirect($this->router->pathFor('logout'));
    }
}