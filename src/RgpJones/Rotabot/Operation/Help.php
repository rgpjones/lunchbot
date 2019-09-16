<?php
namespace RgpJones\Rotabot\Operation;

use Pimple\Container;

class Help implements Operation
{
    /**
     * @var Container
     */
    private $operations;

    public function __construct(Container $operations)
    {
        $this->operations = $operations;
    }

    public function getUsage()
    {
        return '`help`: Display this help text';
    }

    public function run(array $args, $username)
    {
        $response = '/rota <operation>' . PHP_EOL;

        foreach ($this->operations->keys() as $key) {
            $response .= $this->operations[$key]->getUsage() . "\n";
        }

        return $response;
    }
}
