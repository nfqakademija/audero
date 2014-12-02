<?php

namespace Audero\ShowphotoBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SinglePhotoIsSetValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if (strlen($protocol->getPhotoUrl()) != 0 && $protocol->getPhotoFile() != null ||
            strlen($protocol->getPhotoUrl()) == 0 && $protocol->getPhotoFile() == null
        ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}