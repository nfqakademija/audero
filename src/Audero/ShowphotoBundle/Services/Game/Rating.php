<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\Rating as PRating;
use Audero\WebBundle\Services\Pusher\PusherQueue;

class Rating {

    private $pusherQueue;

    public function __construct(PusherQueue $pusherQueue) {
        $this->pusherQueue = $pusherQueue;
    }

    public function broadcast(PRating $rating) {
        $requestSlug  = $rating->getResponse()->getRequest()->getSlug();
        $responseAuthor = $rating->getResponse()->getUser()->getUsername();
        $likes = $rating->getResponse()->getLikes();
        $dislikes = $rating->getResponse()->getDislikes();
        $likesPercent = 0;
        $dislikesPercent = 0;
        if(($likes + $dislikes) > 0) {
            $likesPercent = round(($likes / ($likes + $dislikes))*100,0);
            $dislikesPercent = 100 - $likesPercent;
        }

        $data = array(
            'command' => 'push',
            'topic' => 'rating',
            'data' => array(
                'requestSlug' => $requestSlug,
                'responseAuthor' => $responseAuthor,
                'likes' => $likes,
                'dislikes' => $dislikes,
                'likesPercent' => $likesPercent,
                'dislikesPercent' => $dislikesPercent,
            )
        );

        $this->pusherQueue->add($data);
    }

}