<?php

namespace Audero\ShowphotoBundle;

final class RatingEvents
{
    /**
     * The rate.photo event is thrown each time a rating is updated
     *
     * The event listener receives an
     * Audero\ShowphotoBundle\Event\FilterRatingEvent instance.
     *
     * @var string
     */
    const RATE_PHOTO = 'rate.photo';
}