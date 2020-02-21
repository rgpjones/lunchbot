<?php

namespace RgpJones\Rotabot\Operation;

use Pimple\Container;

class OperationDelegator
{
    private $operationProvider;

    public function __construct(OperationProvider $operationProvider)
    {
        $this->operationProvider = $operationProvider;
    }

    public function runOperation(Container $container): string
    {
        $this->operationProvider->register($container);

        $input = explode(' ', $container['text'], 2);

        $operationName = strtolower(array_shift($input));

        $operation = $this->getOperation($container, $operationName);

        return (string) $operation->run($input, $container['username']);
    }

    protected function getOperation(Container $container, string $operationName): Operation
    {
        return $container['operations']->offsetExists($operationName)
            ? $container['operations'][$operationName]
            : $container['operations']['help'];
    }
}
