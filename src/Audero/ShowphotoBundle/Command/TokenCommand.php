<?php

namespace Audero\ShowphotoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('token:provider:start')
        ->setDescription('Start token provider');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tokenProvider = $this->getContainer()->get('token.provider');
        $tokenProvider->start();
    }
}