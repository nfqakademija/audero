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
        return '8bb822e208f3b6474fc57e6ce48090948baaa920';
    }
} 