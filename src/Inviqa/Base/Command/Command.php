<?php

namespace Inviqa\Base\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Inviqa\Base\CommandConfigurator\CommandConfigurator;

abstract class Command extends SymfonyCommand
{
    public function addCommandConfigurator(CommandConfigurator $commandConfigurator)
    {
        $commandConfigurator->configure($this);
    }
}
