<?php

namespace App\Controller;

use Pimple\Container;
use RgpJones\Rotabot\Notifier\SlackConfiguration;
use RgpJones\Rotabot\RotabotService;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Notifier\Slack;
use RgpJones\Rotabot\Storage\FileStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function api(RotabotService $rotabotService, SlackConfiguration $slackConfiguration): Response
    {
        return $this->prepareResponse((string) $rotabotService->run($slackConfiguration));
    }

    protected function prepareResponse(string $response): Response
    {
        if (strlen($response) > 0) {
            return new Response($response, Response::HTTP_OK);
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
