<?php

namespace Audero\ShowphotoBundle\Services\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Imgur {

    private $tokenProvider;

    public function __construct(TokenProvider $tokenProvider)
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
     *  Passes image's url to imgur
     *  Return type JSON
     * */
    public function uploadUrl($image)
    {
        $post = array(
            'image' => $image
        );

        return $this->upload($post);
    }

    /*
     *  Passes file to imgur
     *  Return type JSON
     * */
    public function uploadFile(UploadedFile $file)
    {
        $post = array(
            'image' => '@'.$file->getPath().'/'.$file->getBasename()
        );

        return $this->upload($post);
    }

} 