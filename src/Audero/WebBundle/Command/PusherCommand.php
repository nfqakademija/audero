<?php

namespace Audero\WebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use Ratchet\Session\SessionProvider;

class PusherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('pusher:start')
        ->setDescription('Start the Pusher');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop   = Factory::create();
        $pusher = $this->getContainer()->get('pusher');

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5557'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'execute'));


        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server($loop);
        $webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect

        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new SessionProvider(
                        new WampServer(
                            $pusher
                        ), $this->getContainer()->get('session.handler.pdo')
                    )

                )
            ),
            $webSock
        );

        $loop->run();
    }
}