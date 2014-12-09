<?php

namespace Audero\WebBundle\Services\Pusher\Pusher;

interface OutputInterface {

    public function error($entity, $text);

    public function notification($entity, $text);
}