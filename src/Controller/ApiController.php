<?php

namespace App\Controller;

use Pimple\Container;
use RgpJones\Rotabot\Operation\OperationDelegator;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Messenger\Slack;
use RgpJones\Rotabot\Messenger\SlackCredentials;
use RgpJones\Rotabot\Storage\FileStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function api(
        Request $request,
        OperationDelegator $operationDelegator,
        SlackCredentials $slackCredentials
    ): Response {

        if ($request->get('token') != $slackCredentials->getSlackToken()) {
            throw new \RunTimeException('The request did not provide the correct Slack authorisation token');
        }

        $config = $this->buildConfigEntity($request, $slackCredentials);

        $container = new Container();
        $container['config'] = $config;
        $container['username'] = $request->get('user_name');
        $container['text'] = trim($request->get('text'));

        $container = $this->registerServices($container);

        return $this->prepareResponse((string) $operationDelegator->runOperation($container));
    }



    protected function buildConfigEntity(Request $request, SlackCredentials $slackCredentials): \stdClass
    {
        $config = new \stdClass;
        $config->token = $slackCredentials->getSlackToken();
        $config->webhook = $slackCredentials->getSlackWebhookUrl();
        $config->user = $request->get('user_name');
        $config->channel = $request->get('channel_name');
        return $config;
    }

    protected function registerServices(Container $container): Container
    {
        $container['storage'] = function () use ($container) {
            return new FileStorage($container['config']->channel);
        };

        $container['rota_manager'] = function () use ($container) {
            return new RotaManager($container['storage']);
        };

        $container['messenger'] = function () use ($container) {
            return new Slack($container['config']);
        };

        return $container;
    }

    protected function prepareResponse(string $response): Response
    {
        if (strlen($response) > 0) {
            return new Response($response, Response::HTTP_OK);
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
