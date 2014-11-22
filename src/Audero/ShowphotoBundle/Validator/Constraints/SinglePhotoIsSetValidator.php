<?php

namespace Audero\ShowphotoBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SinglePhotoIsSetValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if($protocol->getPhotoUrl() != '' && $protocol->getPhotoFile() != null ||
            trim($protocol->getPhotoUrl()) == '' && $protocol->getPhotoFile() == null ) {
            $this->context->addViolationAt(
                'photo',
                $constraint->message,
                array(),
                null
            );
        }
    }
}