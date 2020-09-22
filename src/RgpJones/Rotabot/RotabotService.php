<?php

namespace RgpJones\Rotabot;

use Pimple\Container;
use RgpJones\Rotabot\Notifier\Slack;
use RgpJones\Rotabot\Notifier\SlackConfiguration;
use RgpJones\Rotabot\Operation\OperationDelegator;
use RgpJones\Rotabot\Storage\StorageFactory;

class RotabotService
{
    private $operationDelegator;

    private $storageFactory;

    public function __construct(OperationDelegator $operationDelegator, StorageFactory $storageFactory)
    {
        $this->operationDelegator = $operationDelegator;
        $this->storageFactory = $storageFactory;
    }

    public function run(SlackConfiguration $slackConfiguration)
    {
        $container = new Container();
        $container['config'] = $slackConfiguration;
        $container['username'] = $slackConfiguration->getUser();
        $container['text'] = $slackConfiguration->getInputText();

        $container = $this->registerServices($container);

        return $this->operationDelegator->runOperation($container);
    }

    protected function registerServices(Container $container): Container
    {
        $container['storage'] = function () use ($container) {
            return $this->storageFactory->getStorage($container['config']->getChannel());
        };

        $container['rota_manager'] = function () use ($container) {
            return new RotaManager($container['storage']);
        };

        $container['messenger'] = function () use ($container) {
            return new Slack($container['config']);
        };

        return $container;
    }
}
