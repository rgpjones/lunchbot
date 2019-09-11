<?php
namespace RgpJones\Rotabot;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CommandProvider implements ServiceProviderInterface
{
    /**
     * Registers commands in the container
     *
     * @param Container $app A Container instance
     */
    public function register(Container $app)
    {

        $app['commands'] = new Container;

        $app['commands']['cancel'] = function () use ($app) {
            return new Command\Cancel($app['rota_manager'], $app['slack']);
        };

        $app['commands']['hello'] = function () use ($app) {
            return new Command\Hello($app['slack']);
        };

        $app['commands']['help'] = function () use ($app) {
            return new Command\Help($app['commands']);
        };

        $app['commands']['join'] = function () use ($app) {
            return new Command\Join($app['rota_manager'], $app['slack']);
        };

        $app['commands']['kick'] = function () use ($app) {
            return new Command\Kick($app['rota_manager'], $app['slack']);
        };

        $app['commands']['leave'] = function () use ($app) {
            return new Command\Leave($app['rota_manager'], $app['slack']);
        };

        $app['commands']['paid'] = function () use ($app) {
            return new Command\Paid($app['rota_manager']);
        };

        $app['commands']['rota'] = function () use ($app) {
            return new Command\Rota($app['rota_manager'], $app['slack']);
        };

        $app['commands']['skip'] = function () use ($app) {
            return new Command\Skip($app['rota_manager'], $app['commands']['who']);
        };

        $app['commands']['swap'] = function () use ($app) {
            return new Command\Swap($app['rota_manager'], $app['slack']);
        };

        $app['commands']['who'] = function () use ($app) {
            return new Command\Who($app['rota_manager'], $app['slack']);
        };

        $app['commands']['whopaid'] = function () use ($app) {
            return new Command\Whopaid($app['rota_manager'], $app['slack']);
        };
    }
}
