<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/vin")
 */
class VinController extends AbstractController
{
    /**
     * @Route("/", name="account-vin")
     * @return Response
     */
    public function indexAction()
    {
        return new Response('success!');
    }
}
