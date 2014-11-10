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

    private function getNewToken() {
        $timeout = 10;
        $post = array(
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token'
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/oauth2/token');
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($curl);
        $data = json_decode($response);
        curl_close ($curl);
        if(isset($data->access_token)) //Isset also will make sure $content is set
        {
            return $data->access_token;
        }

        return null;
    }

    /*
     *  Returns Imgur token
     * */
    public function getToken()
    {
        return $this->getNewToken();
    }
} 