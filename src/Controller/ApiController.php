<?php

namespace App\Controller;

use Monolog\Logger;
use RgpJones\Rotabot\Application;
use RgpJones\Rotabot\Slack\SlackCredentials;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function api(Request $request, SlackCredentials $slackCredentials): Response
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
                'logger' => $this->logger
            ]
        );

        $response = (string) $app->run();

        if (strlen($response) > 0) {
            return new Response($response, Response::HTTP_OK);
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
