<?php

namespace AppBundle\Specifications\Domain;

use AppBundle\Specifications\Specification;

/**
 * Class IsNotNull.
 */
class IsNotNull extends Specification
{
    /**
     * @param $object
     *
     * @return bool
     */
    public function isSatisfiedBy($object)
    {
        return $object !== null;
    }
}
