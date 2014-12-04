<?php

namespace Audero\ShowphotoBundle\Event;

use Audero\ShowphotoBundle\Entity\Rating;
use Symfony\Component\EventDispatcher\Event;

class FilterRatingEvent extends Event
{
    protected $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    public function getRating()
    {
        return $this->rating;
    }
}