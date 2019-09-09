<?php
namespace RgpJones\Rotabot\Command;

use Pimple\Container;
use RgpJones\Rotabot\Command;

class Help implements Command
{
    /**
     * @var Container
     */
    private $commands;

    public function __construct(Container $commands)
    {
        $this->commands = $commands;
    }

    public function getUsage()
    {
        return '`help`: Display this help text';
    }

    public function run(array $args, $username)
    {
        $response = "/rota <command>\n";

        foreach ($this->commands->keys() as $key) {
            $response .= $this->commands[$key]->getUsage() . "\n";
        }

        return $response;
    }
}
