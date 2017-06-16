<?php

namespace AppBundle\Specifications;

/**
 * Interface SpecificationInterface.
 */
interface SpecificationInterface
{
    /**
     * @param $object
     *
     * @return mixed
     */
    public function isSatisfiedBy($object);

    /**
     * @param SpecificationInterface $specification
     *
     * @return mixed
     */
    public function andSpec(SpecificationInterface $specification);
}
