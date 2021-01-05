<?php

namespace App\Controllers;

use Panel\Life\Gang;
use Panel\Life\Home;
use Panel\Life\Player;
use Panel\Life\Vehicle;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RequestController extends Controller {

    public function postRequest(RequestInterface $request, ResponseInterface $response)
    {
        function requester(){
            if (isset($_POST['req'])) {
                $request = htmlspecialchars($_POST['req']);
                switch ($request) {

//Player Request
                    case 'editPlayer':
                        if(empty($_POST['pid'])) return 'UserNotFound';
                        $Player = new Player($pid=htmlspecialchars($_POST['pid']));
                        return $Player->edit(
                            (int) htmlspecialchars($_POST['cash'] ?? ''),
                            (int) htmlspecialchars($_POST['bank'] ?? ''),
                            (int) htmlspecialchars($_POST['cop'] ?? ''),
                            (int) htmlspecialchars($_POST['med'] ?? ''),
                            (int) htmlspecialchars($_POST['admin'] ?? '')
                        );
                        break;
                    case 'editPlayerLicense':
                        if(empty($_POST['pid'])) return 'UserNotFound';
                        $Player = new Player($pid=htmlspecialchars($_POST['pid']));
                        return $Player->license(
                            (string) htmlspecialchars($_POST['type'] ?? ''),
                            (string) htmlspecialchars($_POST['name'] ?? ''),
                            (int) htmlspecialchars($_POST['value'] ?? '')
                        );
                        break;
                    case 'refundPlayer':
                        if(empty($_POST['pid'])) return 'UserNotFound';
                        $Player = new Player($pid=htmlspecialchars($_POST['pid']));
                        return $Player->refund(
                            (int) htmlspecialchars($_POST['amount'] ?? ''),
                            (string) htmlspecialchars($_POST['message'] ?? ''),
                            (string) htmlspecialchars($_POST['evidence'] ?? '')
                        );
                        break;
// Vehicle Request
                    case 'deleteVehicle':
                        if(empty($_POST['id'])) return 'VehicleNotFound';
                        $Vehicle = new Vehicle((int) htmlspecialchars($_POST['id']));
                        return $Vehicle->delete();
                        break;
//Houses Request
                    case 'deleteHome':
                        if(empty($_POST['id'])) return 'HouseNotFound';
                        $home = new Home((int) htmlspecialchars($_POST['id']));
                        return $home->delete();
                        break;
//Gang Request
                    case 'getGangMember':
                        if(empty($_POST['gangid'])) return 'NoGangFound';
                        $Gang = new Gang((int) htmlspecialchars($_POST['gangid']));
                        return $Gang->memberList();
                        break;

/*
//Staff Request
                    case 'addStaff':
                        if(empty($_POST['pid'])) return 'UserNotFound';
                        require(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'Staff.php');
                        return Staff::addStaff(htmlspecialchars($_POST['pid']), htmlspecialchars($_POST['rank']));
                        break;*/

                    default:
                        return 404;
                        break;
                }
            } return 404;
        }
        echo requester();
    }
}