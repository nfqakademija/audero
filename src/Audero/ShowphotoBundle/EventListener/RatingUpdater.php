<?php

namespace Audero\ShowphotoBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Audero\ShowphotoBundle\Entity\Rating;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class RatingUpdater
{
    private $recalculatePlayerRank = false;

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof Rating) {
                $entity->setRate('0');
                $uow->recomputeSingleEntityChangeSet(new ClassMetadata($em->getClassMetadata('Audero\ShowphotoBundle\Entity\Rating')), $entity);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
    }
}