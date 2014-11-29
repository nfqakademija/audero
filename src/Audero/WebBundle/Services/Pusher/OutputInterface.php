<?php

namespace Audero\WebBundle\Services\Pusher;

interface OutputInterface {

    public function error($text);

    public function notification($text);
}