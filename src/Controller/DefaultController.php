<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
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

    /**
     * @Route("/", methods={"POST"})
     */
    public function api(Request $request)
    {
        return new Response(
            'TOKEN: ' . $request->get('token')
        );
    }
}