<?php

namespace App\Controller;

use RgpJones\Rotaman\Application;
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
        $config = simplexml_load_file(__DIR__ . '/../../config.xml');

        if ($request->get('token') != $config->token) {
            throw new \RunTimeException('Invalid Request');
        }

        $config->user = $request->get('user_name');
        $config->channel = $request->get('channel_name');

        $app = new Application(
            [
                'config' => $config,
            ]
        );
        $app->run();
//        return new Response(
//            'TOKEN: ' . $request->get('token')
//        );
    }
}