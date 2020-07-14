<?php
namespace RgpJones\Rotabot\Operation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OperationProvider implements ServiceProviderInterface
{
    /**
     * Registers operations in the container
     *
     * @param Container $app A Container instance
     */
    public function register(Container $app)
    {
        $app['operations'] = new Container;

        $app['operations']['cancel'] = function () use ($app) {
            return new Cancel($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['hello'] = function () use ($app) {
            return new Hello($app['messenger']);
        };

        $app['operations']['help'] = function () use ($app) {
            return new Help($app['operations']);
        };

        $app['operations']['join'] = function () use ($app) {
            return new Join($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['kick'] = function () use ($app) {
            return new Kick($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['leave'] = function () use ($app) {
            return new Leave($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['ping'] = function () {
            return new Ping();
        };

        $app['operations']['rota'] = function () use ($app) {
            return new Rota($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['skip'] = function () use ($app) {
            return new Skip($app['rota_manager'], $app['operations']['who']);
        };

        $app['operations']['swap'] = function () use ($app) {
            return new Swap($app['rota_manager'], $app['messenger']);
        };

        $app['operations']['who'] = function () use ($app) {
            return new Who($app['rota_manager'], $app['messenger']);
        };
    }
}
