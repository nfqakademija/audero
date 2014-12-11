<?php

namespace Audero\ShowphotoBundle\Services\Uploader;

use Audero\ShowphotoBundle\Entity\Token as TokenEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Imgur
 * @package Audero\ShowphotoBundle\Services\Uploader
 */
class Imgur
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $post
     * @return mixed|null
     */
    private function upload($post)
    {
        $repo = $this->em->getRepository("AuderoShowphotoBundle:Token");
        if (!$repo) {
            return null;
        }
        $token = $repo->findNewest();
        /**@var TokenEntity $token*/
        if (!$token || !$token->getAccessToken()) {
            return null;
        }

        $timeout = 10;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/upload');
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token->getAccessToken()
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response) : null;
    }

    /**
     * @param $image
     * @return mixed|null
     */
    public function uploadUrl($image)
    {
        $post = array(
            'image' => $image
        );

        return $this->upload($post);
    }

    /**
     * @param UploadedFile $file
     * @return mixed|null
     */
    public function uploadFile(UploadedFile $file)
    {
        $post = array(
            'image' => '@' . $file->getPath() . '/' . $file->getBasename()
        );

        return $this->upload($post);
    }
} 