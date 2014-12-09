<?php

namespace Audero\ShowphotoBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SinglePhotoIsSet extends Constraint
{
    public $message = 'Single photo is not set';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}