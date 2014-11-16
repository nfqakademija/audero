<?php

namespace Audero\ShowphotoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GameCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('game:start')
        ->setDescription('Start the Game');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('game.manager');
        $manager->start();
    }
}