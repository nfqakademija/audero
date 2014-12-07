<?php

namespace Audero\ShowphotoBundle\EventListener;

use Audero\ShowphotoBundle\Services\Game\Rating as RatingService;
use Audero\ShowphotoBundle\Entity\Rating;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class RatingListener
 * @package Audero\ShowphotoBundle\EventListener
 */
class RatingListener
{
    /**
     * @var RatingService
     */
    private $ratingService;

    /**
     * @param RatingService $ratingService
     */
    public function __construct(RatingService $ratingService) {
        $this->ratingService = $ratingService;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $em->getConnection()->beginTransaction();
        try{
            foreach ($uow->getScheduledEntityInsertions() as $entity) {
                if ($entity instanceof Rating) {
                    if($entity->getRate() == true) {
                        $entity->getResponse()->changeLikesValueBy(1);
                        $entity->getResponse()->getUser()->changeRateBy(1);
                    }else{
                        $entity->getResponse()->changeDisLikesValueBy(1);
                        $entity->getResponse()->getUser()->changeRateBy(-1);
                    }

                    $response = $entity->getResponse();
                    if(!$response) {
                        throw new InternalErrorException();
                    }
                    $user = $entity->getResponse()->getUser();
                    if(!$user) {
                        throw new InternalErrorException();
                    }

                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $response);
                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $user);
                    $this->ratingService->broadcast($entity);
                }
            }

            foreach ($uow->getScheduledEntityUpdates() as $entity) {
                if ($entity instanceof Rating) {
                    $changeSet = $uow->getEntityChangeSet($entity);
                    if(isset($changeSet['rate'])) {
                        if($entity->getRate() == true) {
                            $entity->getResponse()->changeLikesValueBy(1)
                                ->changeDisLikesValueBy(-1);
                            $entity->getResponse()->getUser()->changeRateBy(2);
                        }else{
                            $entity->getResponse()->changeLikesValueBy(-1)
                                ->changeDisLikesValueBy(1);
                            $entity->getResponse()->getUser()->changeRateBy(-2);
                        }

                        $response = $entity->getResponse();
                        if(!$response) {
                            throw new InternalErrorException();
                        }
                        $user = $entity->getResponse()->getUser();
                        if(!$user) {
                            throw new InternalErrorException();
                        }

                        $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $response);
                        $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $user);
                        $this->ratingService->broadcast($entity);
                    }
                }
            }

            foreach ($uow->getScheduledEntityDeletions() as $entity) {
                if ($entity instanceof Rating) {
                    if($entity->getRate() == true) {
                        $entity->getResponse()->changeLikesValueBy(-1);
                        $entity->getResponse()->getUser()->changeRateBy(-1);
                    }else{
                        $entity->getResponse()->changeDislikesValueBy(-1);
                        $entity->getResponse()->getUser()->changeRateBy(1);
                    }

                    $response = $entity->getResponse();
                    if(!$response) {
                        throw new InternalErrorException();
                    }
                    $user = $entity->getResponse()->getUser();
                    if(!$user) {
                        throw new InternalErrorException();
                    }

                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $response);
                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $user);
                    $this->ratingService->broadcast($entity);
                }
            }

            foreach ($uow->getScheduledCollectionDeletions() as $col) {
                //
            }

            foreach ($uow->getScheduledCollectionUpdates() as $col) {
                //
            }

        }catch (\Exception $e) {
            $em->getConnection()->rollBack();
            throw $e;
        }

        $em->getConnection()->commit();
    }
}