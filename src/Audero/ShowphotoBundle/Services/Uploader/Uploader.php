<?php

namespace Audero\ShowphotoBundle\Services\Uploader;


class Uploader {

    private $tokenProvider;

    public function __construct($tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
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
            'Authorization: Bearer '.$this->tokenProvider->getToken()
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
    public function uploadPhotoUrl($url)
    {
        $post   = array(
            'image' => $url,
            'type'  =>'url');

        return $this->upload($post);
    }

    public function uploadPhotoFile()
    {

    }

} 