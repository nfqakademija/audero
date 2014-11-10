<?php

namespace Audero\ShowphotoBundle\Uploader;

use Audero\ShowphotoBundle\Entity\User;
use Audero\ShowphotoBundle\Entity\PhotoRequest;

class Manager {

    private $uploader;

    public function __construct($uploader)
    {
        $this->uploader = $uploader;
    }

    public function createResponse(User $user, PhotoRequest $request)
    {

    }
}