<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Services\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class Manager implements OutputInterface
{

    private $em;
    private $pRequestService;
    private $playerManager;

    public function __construct(EntityManager $em, PhotoRequest $pRequestService, PlayerManager $playerManager)
    {
        $this->em = $em;
        $this->pRequestService = $pRequestService;
        $this->playerManager = $playerManager;
    }

    public function start()
    {
        while (true) {
            /*Getting admin options*/
            $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
            if (!$options) {
                $this->error("Admin options not found");
                sleep(5);
                continue;
            }

            /*Generating new Request*/
            $data = $this->pRequestService->generate();
            if (!isset($data['request']) || !isset($data['wish'])) {
                $this->error("Could not get newly generated request");
                sleep(10);
                continue;
            }

            $pRequestEntity = $data['request'];
            $wish = $data['wish'];



            // wish -> request
            /*            try{
                            $this->em->remove($wish);
                            $this->em->persist($request);
                            $this->em->flush();
                        }catch (\Exception $e) {
                            echo $e->getMessage(); die;
                        }*/


            //

            $this->pRequestService->broadcast($pRequestEntity);

            // TODO
            $date = new \DateTime('now');
            $sleepTime = $this->pRequestService->getValidUntil($pRequestEntity) - $date->getTimestamp();
            sleep($sleepTime);
        }
    }

    public function error($text)
    {
        echo "Game Manager error: " . $text . "\n";
    }

    public function notification($text)
    {
        echo "Game Manager: " . $text . "\n";
    }
}