<?php

namespace Audero\ShowphotoBundle\Uploader;


class Uploader {

    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /*
     * Uploads photo to imgur
     * Returns JSON response
     * */
    private function upload($post)
    {
        $timeout = 10;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/upload');
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->token->get()
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($curl);
        curl_close ($curl);
        return $response;
    }

    /*
     *  Uploads image to imgur from passed url
     *  Return type JSON
     * */
    public function uploadFromUrl($url)
    {
        $post   = array(
            'image' => $url,
            'type'  =>'url');

        return $this->upload($post);
    }

    public function uploadFromFile()
    {

    }

} 