<?php

namespace Audero\ShowphotoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PhotoRequestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PhotoRequestRepository extends EntityRepository
{
    public function findNewestRequest()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM AuderoShowphotoBundle:PhotoRequest r ORDER BY r.date DESC')
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findOneBySlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM AuderoShowphotoBundle:PhotoRequest r WHERE r.slug = ?1')
            ->setParameter(1, $slug)
            ->getOneOrNullResult();
    }
}
