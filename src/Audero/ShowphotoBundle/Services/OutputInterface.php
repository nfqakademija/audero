<?php

namespace Audero\ShowphotoBundle\Services;

interface OutputInterface {

    public function error($text);

    public function notification($text);
}