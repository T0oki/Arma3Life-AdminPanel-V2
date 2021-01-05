<?php

use App\Controllers\AdminController;
use App\Controllers\ConnectionController;
use App\Controllers\LifeController;
use App\Controllers\RequestController;
use App\Controllers\StaffController;
use App\Middlewares\ActualiseMiddleware;
use Panel\Utile;

require (__DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

session_name('ArmaPanel');
session_start();

$app = new Slim\App();

require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'container.php');

$container = $app->getContainer();
Utile::init($container->get('router'));

$container->view->getEnvironment()->addGlobal('web_url', 'http://DOMAIN.EXT/');
$container->view->getEnvironment()->addGlobal('session', $_SESSION);

//Life
$app->get('/', LifeController::class . ':home')->setName('home')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/profile[/[{pid}]]', LifeController::class. ":profile")->setName('profile')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/joueurs', LifeController::class. ":player")->setName('player')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/vehicules', LifeController::class. ":vehicle")->setName('vehicle')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/residences', LifeController::class. ":house")->setName('house')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/conteneurs', LifeController::class. ":container")->setName('container')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));
$app->get('/gangs', LifeController::class. ":gang")->setName('gang')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));

//Staff
$app->get('/remboursement', StaffController::class. ":refund")->setName('refund')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));

//Admin
$app->get('/staff', AdminController::class. ":staff")->setName('staff')->add(new ActualiseMiddleware($container->get('router'), $container->view->getEnvironment()));


$app->get('/contact', LifeController::class . ':getContact')->setName('contact');
$app->post('/contact', LifeController::class . ':postContact');

// Connexions Pages
$app->get('/register', ConnectionController::class . ':getRegister')->setName('register');
$app->get('/login', ConnectionController::class . ':getLogin')->setName('login');
$app->get('/logout', ConnectionController::class . ':getLogout')->setName('logout');

// Requettes pages
$app->post('/signup', ConnectionController::class . ':postRegister')->setName('r_register');
$app->post('/connect', ConnectionController::class . ':postLogin')->setName('r_connect');
$app->post('/request', RequestController::class . ':postRequest')->setName('r_request');

$app->run();