<?php

namespace Audero\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * OptionsRecordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OptionsRecordRepository extends EntityRepository
{
    private $serializer;

    public function __construct($em, ClassMetadata $class) {
        parent::__construct($em, $class);
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function findCurrent() {
        $record =  $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AuderoBackendBundle:OptionsRecord r ORDER BY r.date DESC'
            )
            ->setMaxResults(1)
            ->getResult();

        if($record) {
            return $this->serializer->deserialize($record[0]->getObject(), 'Audero\BackendBundle\Entity\Options','json');
        }

        return null;
    }
}
