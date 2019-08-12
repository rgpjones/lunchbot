<?php

namespace Inviqa\Base\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('configure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input->setInteractive(true);
    }
}
