<?php

namespace AppBundle\Specifications\Domain;

use AppBundle\Specifications\Specification;

/**
 * Class IsValidDate.
 */
class IsValidDate extends Specification
{
    /**
     * @param $object
     *
     * @return bool
     */
    public function isSatisfiedBy($object): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $object);
    }
}
