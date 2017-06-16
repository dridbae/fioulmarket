<?php

namespace AppBundle\Specifications;

/**
 * Class Specification.
 */
abstract class Specification implements SpecificationInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return AndSpecification
     */
    public function andSpec(SpecificationInterface $specification)
    {
        return new AndSpecification($this, $specification);
    }
}
