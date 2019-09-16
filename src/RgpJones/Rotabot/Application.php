<?php

namespace RgpJones\Rotabot;

use RgpJones\Rotabot\Operation\OperationProvider;
use RgpJones\Rotabot\Slack\Slack;
use RgpJones\Rotabot\Storage\FileStorage;
use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Request;

class Application extends BaseApplication
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $app = $this;
        $app['config'] = $values['config'];
        $app['logger'] = $values['logger'];

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

        $app->register(new OperationProvider);

        $app->post(
            '/',
            function (Request $request) use ($app) {

                $argv = explode(' ', trim($request->get('text')));
                $operationName = strtolower(array_shift($argv));

                $app['logger']->info('Received operation ' . $operationName);

                /** @var Operation\Operation $operation */
                $operation = $app['operations']->offsetExists($operationName)
                    ? $app['operations'][$operationName]
                    : $app['operations']['help'];

                $result = (string) $operation->run($argv, $request->get('user_name'));

                $app['logger']->info('Result: ' . $result);

                return $result;
            }
        );
    }
}
