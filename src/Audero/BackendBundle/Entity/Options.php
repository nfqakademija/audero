<?php

namespace Audero\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Options
{
    private $photoUploadInterval;

    public function setPhotoUploadInterval($photoUploadInterval)
    {
        $this->photoUploadInterval = $photoUploadInterval;
    }

    public function getPhotoUploadInterval()
    {
        return $this->photoUploadInterval;
    }
}
