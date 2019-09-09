<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /**
     * @Route("/", methods={"GET","HEAD"})
     */
    public function index()
    {
        return new Response(
            '<h1>Is it my turn?</h1>'
        );
    }
}
