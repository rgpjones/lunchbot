<?php

namespace App\Controller;

use Pimple\Container;
use RgpJones\Rotabot\Notifier\SlackConfiguration;
use RgpJones\Rotabot\Operation\OperationDelegator;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Notifier\Slack;
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
        SlackConfiguration $slackConfiguration
    ): Response {

        $container = new Container();
        $container['config'] = $slackConfiguration;
        $container['username'] = $request->get('user_name');
        $container['text'] = trim($request->get('text'));

        $container = $this->registerServices($container);

        return $this->prepareResponse((string) $operationDelegator->runOperation($container));
    }



    protected function registerServices(Container $container): Container
    {
        $container['storage'] = function () use ($container) {
            return new FileStorage($container['config']->getChannel());
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
