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

    public function upload()
    {
        echo 'veikia';
    }

} 