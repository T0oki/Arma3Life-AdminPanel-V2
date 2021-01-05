<?php

namespace App\Controllers;

use Panel\Liste;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class StaffController extends Controller {

    public function refund(RequestInterface $request, ResponseInterface $response, $args)
    {
        $this->addVar('table_refund', Liste::refund());
        $this->render($response, 'pages/staff/refund.twig');
    }

}