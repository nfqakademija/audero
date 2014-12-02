<?php

namespace Audero\WebBundle\Services\Pusher;

interface OutputInterface {

    public function error($entity, $text);

    public function notification($entity, $text);
}