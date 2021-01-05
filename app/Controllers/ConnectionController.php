<?php

namespace App\Controllers;

use Panel\Database;
use Panel\Request\Connect;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class ConnectionController extends Controller {

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed|void
     */
    public function getLogin(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION['Statut'])) return $this->redirect($response, 'home');
        else return $this->render($response, 'pages/connexion/login.twig');
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed|void
     */
    public function getRegister(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION['Statut'])) return $this->redirect($response, 'home');
        else {
            require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'steamauth' . DIRECTORY_SEPARATOR . 'steamauth.php');
            $a='';
            if(!isset($_SESSION['steamid'])) {

                $a = loginbutton(); //login button

            }  else {

                include(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'steamauth' . DIRECTORY_SEPARATOR . 'userInfo.php'); //To access the $steamprofile array
                //Protected content
            }
            return $this->render($response, 'pages/connexion/register.twig', ['lgbtn' => $a, 'steam' => $steamprofile ?? 0]);
        }
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return string
     */
    public function postLogin(RequestInterface $request, ResponseInterface $response)
    {
        $errors = [];
        Validator::email()->validate($request->getParam('mail')) || $errors['mail'] = true;
        Validator::notEmpty()->validate($request->getParam('pass')) || $errors['pass'] = true;
        if (empty($errors)) {
            Connect::init($request->getParam('mail'), $request->getParam('pass'));
            return Connect::connexion();
        } else {
            return 'NO_INFORMATION';
        }
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return string
     */
    public function postRegister(RequestInterface $request, ResponseInterface $response)
{
    $errors = [];
    if (isset($_POST['mail']) &&
        isset($_POST['pass']) &&
        isset($_POST['uid']) &&
        isset($_POST['steamurl']) &&
        isset($_POST['steamavatar']) &&
        isset($_POST['steamrealname']))
    {
        $email = htmlspecialchars(strtolower($_POST['mail']));
        $password = htmlspecialchars($_POST['pass']);
        $uid = htmlspecialchars($_POST['uid']);
        $url = htmlspecialchars($_POST['steamurl']);
        $avatar = htmlspecialchars($_POST['steamavatar']);
        $realname = htmlspecialchars($_POST['steamrealname']);

        $web_db = Database::web();
        $reponse = $web_db->query("SELECT * FROM Utilisateurs WHERE UID='$uid'");
        $result = $reponse->fetch();
        if ($result['UID'] === $uid) {
            $errors = "UID";
        } else {
            $reponse = $web_db->query("SELECT * FROM Utilisateurs WHERE Email='$email'");
            $result = $reponse->fetch();
            if ($result['Email'] === $email) {
                $errors = "EMAIL";
            } else {
                $server_db = Database::server();
                $reponse = $server_db->query("SELECT name FROM players WHERE pid='$uid'");
                $result = $reponse->fetch();
                $name = $result['name'] ?? $realname;
                $req = $web_db->prepare("INSERT INTO Utilisateurs(UID, Email, insert_time, Nom, Password, steamurl, avatar, realname) VALUES(:UID, :Email, :insert_time, :Nom, :Password, :steamurl, :avatar, :realname)");
                $req->execute(array(
                    'UID' => $uid,
                    'Email' => $email,
                    'insert_time' => date("Y-m-d H:i:s"),
                    'Nom' => $name,
                    'Password' => password_hash($password, PASSWORD_DEFAULT),
                    'steamurl' => $url,
                    'avatar' => $avatar,
                    'realname' => $realname,
                ));

                $reponse = $web_db->query("SELECT * FROM Utilisateurs WHERE UID='$uid'");
                $result = $reponse->fetch();
                if ($result['UID'] !== $uid) {
                    $errors = "INSERT";
                } else return "registred";
            }
        }
    }
    else { $errors="NO_INFORMATION"; }
    if ($errors) return "ERROR_".$errors;
}

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function getLogout(RequestInterface $request, ResponseInterface $response)
    {
        session_unset();
        session_destroy();
        return $this->redirect($response, 'login');
    }
}