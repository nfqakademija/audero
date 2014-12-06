<?php

namespace Audero\ShowphotoBundle\EventListener;

use Audero\ShowphotoBundle\Services\Game\Rating as RatingService;
use Audero\ShowphotoBundle\Entity\Rating;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

class RatingListener
{
    private $ratingService;

    public function __construct(RatingService $ratingService) {
        $this->ratingService = $ratingService;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $em->beginTransaction();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Rating) {
                if($entity->getRate() == true) {
                    $entity->getResponse()->changeLikesValueBy(1);
                    $entity->getResponse()->getUser()->changeRateBy(1);
                }else{
                    $entity->getResponse()->changeDisLikesValueBy(1);
                    $entity->getResponse()->getUser()->changeRateBy(-1);
                }

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $entity->getResponse());
                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $entity->getResponse()->getUser());
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

                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $entity->getResponse());
                    $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $entity->getResponse()->getUser());
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

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\PhotoResponse'), $entity->getResponse());
                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata('Audero\ShowphotoBundle\Entity\User'), $entity->getResponse()->getUser());
                $this->ratingService->broadcast($entity);
            }
        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {
            // TODO
        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {
            // TODO
        }

        try{
            $em->commit();
        }catch (\Exception $e) {
            $em->rollback();
            throw new InternalErrorException();
        }
    }
}