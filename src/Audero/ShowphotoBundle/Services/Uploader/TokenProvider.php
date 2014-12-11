<?php

namespace Audero\ShowphotoBundle\Services\Uploader;


use Audero\ShowphotoBundle\Entity\Token as TokenEntity;
use Audero\ShowphotoBundle\Services\OutputInterface;
use Doctrine\ORM\EntityManager;

class TokenProvider implements OutputInterface {

    private $em;
    private $client_id;
    private $client_secret;
    private $refresh_token;

    public function __construct(EntityManager $em, $client_id, $client_secret, $refresh_token)
    {
        $this->em = $em;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
    }

    private function getTokenFromImgur() {
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
        if(!$data || !isset($data->access_token)) {
            return null;
        }

        $newToken = new TokenEntity(); $now = new \DateTime('now');
        $newToken->setAccessToken($data->access_token)
                ->setTokenType($data->token_type)
                ->setExpiresIn($now->add(new \DateInterval('PT'.$data->expires_in.'S')));

        return $newToken;
    }

    public function start() {
        $repo = $this->em->getRepository("AuderoShowphotoBundle:Token");
        if(!$repo) {
            $this->error("Could not get Token's repository"); die;
        }

        while(true)  {
            /**@var TokenEntity $storedToken*/
            $storedToken = $repo->findNewest();
            if(!$storedToken) {
                $this->makeNew();
            }else{
                $now = new \DateTime('now');
                $timeLeft = $storedToken->getExpiresIn()->getTimestamp() - $now->getTimestamp();
                /* less than 15 minutes*/
                if($timeLeft < 900) {
                    $this->makeNew();
                }
            }

            sleep(10);
        }
    }

    private function makeNew() {
        $newToken = $this->getTokenFromImgur();
        if(!$newToken) {
            $this->error("Could not get new token from imgur");
            return null;
        }

        $this->storeToken($newToken);
        $this->notification("Successfully made new token");
        return $newToken;
    }


    private function storeToken(TokenEntity $newToken) {
        try{
            $this->em->persist($newToken);
            $this->em->flush($newToken);
        }catch (\Exception $e) {
            $this->error($e->getMessage()); die;
        }
    }

    public function error($text)
    {
        echo "Error: ".$text."\n";
    }

    public function notification($text)
    {
        echo "Notification: ".$text."\n";
    }
}