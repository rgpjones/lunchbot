<?php

namespace App\Controller;

use RgpJones\Rotabot\Application;
use RgpJones\Rotabot\Slack\SlackCredentials;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function api(Request $request, SlackCredentials $slackCredentials)
    {
        if ($request->get('token') != $slackCredentials->getSlackToken()) {
            throw new \RunTimeException('Invalid Request');
        }

        $config = new \stdClass;
        $config->token = $slackCredentials->getSlackToken();
        $config->webhook = $slackCredentials->getSlackWebhookUrl();
        $config->user = $request->get('user_name');
        $config->channel = $request->get('channel_name');

        $app = new Application(
            [
                'config' => $config,
            ]
        );
        $app->run();

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
