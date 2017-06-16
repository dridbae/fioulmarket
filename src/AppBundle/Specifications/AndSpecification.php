<?php

namespace AppBundle\Specifications;

/**
 * Class AndSpecification.
 */
class AndSpecification extends Specification implements SpecificationInterface
{
    /**
     * @var SpecificationInterface
     */
    private $specification1;
    /**
     * @var SpecificationInterface
     */
    private $specification2;

    /**
     * AndSpecification constructor.
     *
     * @param SpecificationInterface $specification1
     * @param SpecificationInterface $specification2
     */
    public function __construct(SpecificationInterface $specification1, SpecificationInterface $specification2)
    {
        $this->specification1 = $specification1;
        $this->specification2 = $specification2;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    public function isSatisfiedBy($object)
    {
        return $this->specification1->isSatisfiedBy($object)
        && $this->specification2->isSatisfiedBy($object);
    }
}
