<?php
/**
 * Created by PhpStorm.
 * User: rokas
 * Date: 14.10.30
 * Time: 13.21
 */

namespace Audero\ShowphotoBundle\Photos;


class Uploader {

    private $client_id;

    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }


    private function upload($post)
    {
        $timeout = 10;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/upload');
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($curl);
        curl_close ($curl);
        var_dump(json_decode($response,true));
    }

    /*
     *  Uploads image to imgur from passed url
     * */
    public function uploadFromUrl($url)
    {
        $post   = array(
            'image' => $url,
            'type'  =>'url');

        $this->upload($post);
    }

    public function uploadFromFile()
    {

    }

} 