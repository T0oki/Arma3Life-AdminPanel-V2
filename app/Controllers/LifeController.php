<?php

namespace App\Controllers;

use Panel\Liste;
use Panel\Life\Player;
use Panel\Stats;
use Panel\Utile;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class LifeController extends Controller {

    public function home(RequestInterface $request, ResponseInterface $response)
    {
        $this->render($response, 'pages/home.twig', ['values' => Stats::homeStat()]);
    }

    public function profile(RequestInterface $request, ResponseInterface $response, $args)
    {
        if(isset($args['pid'])){
            $pid = (int) $args['pid'];
        } else {
            $pid = (int) $_SESSION['UID'];
        }
        // $this->container->get('router')->pathFor('contact');
        $player = new Player($pid);
        $this->addVar('profile', $player->generateProfileArray());
        $this->addVar('table_vehicle', Liste::vehicle($player->pid));
        $this->addVar('table_house', Liste::house($player->pid));
        $this->addVar('table_container', Liste::container($player->pid));
        $this->addVar('table_refund', Liste::refund($player->pid));
        $this->render($response, 'pages/life/profile.twig');
    }
    public function player(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_player', Liste::player());
        $this->addVar('dropEdit', Liste::dropGroupFastEdit());
        $this->render($response, 'pages/life/player.twig');
    }
    public function vehicle(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_vehicle', Liste::vehicle());
        $this->render($response, 'pages/life/vehicle.twig');
    }
    public function house(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_house', Liste::house());
        $this->render($response, 'pages/life/house.twig');
    }
    public function container(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_container', Liste::container());
        $this->render($response, 'pages/life/container.twig');
    }
    public function gang(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_gang', Liste::gang());
        $this->render($response, 'pages/life/gang.twig');
    }

    public function getContact(RequestInterface $request, ResponseInterface $response)
    {
        $this->render($response, 'pages/contact.twig');
    }
    public function postContact(RequestInterface $request, ResponseInterface $response)
    {
        $errors = [];
        Validator::email()->validate($request->getParam('email')) || $errors['email'] = "Votre email n'est pas valide";
        Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = "Veuillez entrer votre nom";
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = "Veuillez entrer votre nom";
        if (empty($errors)) {
            $message = \Swift_Message::newInstance('Message de contacte')
                ->setFrom([$request->getParam('email') => $request->getParam('name')])
                ->setTo('contact@anthasia.fr')
                ->setBody("Un email vous a été envoyé : 
            {$request->getParam('content')}");
            $this->mailer->send($message);
            $this->flash('Votre message a bien été envoyé');
            return $this->redirect($response, 'contact');
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'contact', 404);
        }
    }
}