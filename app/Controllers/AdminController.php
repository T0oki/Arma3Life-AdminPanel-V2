<?php

namespace App\Controllers;

use Panel\Admin\Staff;
use Panel\Liste;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController extends Controller {

    public function staff(RequestInterface $request, ResponseInterface $response)
    {
        $this->addVar('staffList', Liste::staff());
        $this->render($response, 'pages/admin/staff.twig');
    }
}