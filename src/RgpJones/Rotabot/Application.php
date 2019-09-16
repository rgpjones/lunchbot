<?php

namespace RgpJones\Rotabot;

use RgpJones\Rotabot\Slack\Slack;
use RgpJones\Rotabot\Storage\FileStorage;
use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application extends BaseApplication
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $app = $this;
        $app['config'] = $values['config'];

        $app['storage'] = (array_key_exists('storage', $values))
            ? $values['storage']
            : function () use ($values) {
                return new FileStorage($values['config']->channel);
            };

        $app['rota_manager'] = function () use ($app) {
            return new RotaManager($app['storage']);
        };

        $app['slack'] = function () use ($app) {
            return new Slack($app['config'], $app['debug']);
        };

        $app->register(new CommandProvider);

        $app->post('/', function (Request $request) use ($app) {

                $argv = explode(' ', trim($request->get('text')));
                $commandName = strtolower(array_shift($argv));

                /** @var Command $command */
                $command = $app['commands']->offsetExists($commandName)
                    ? $app['commands'][$commandName]
                    : $app['commands']['help'];

                $response = $command->run($argv, $request->get('user_name'));

                return new Response($response);
        });
    }
}
