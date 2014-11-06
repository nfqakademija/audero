<?php

namespace Audero\ShowphotoBundle\Uploader;


class Token {

    private $client_id;
    private $client_secret;
    private $refresh_token;

    public function __construct($client_id, $client_secret, $refresh_token)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
    }

    /*
     *  Returns Imgur token
     * */
    public function get()
    {
        return 'c8f0946a9fd3cb65000f9d572ea91c5251018d13';
    }
} 