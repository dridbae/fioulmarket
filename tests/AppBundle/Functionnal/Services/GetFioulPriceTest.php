<?php

namespace Tests\Functionnal\AppBundle\Services;

use AppBundle\Services\GetFioulPrice;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GetFioulPriceTest extends WebTestCase
{
    static $container;

    static $repository;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        self::$container = $kernel->getContainer();

        self::$repository = self::$container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:FioulPrice');
    }

    public function testIcanGetAveragePriceFromValidZipCode()
    {
        $class = new GetFioulPrice(self::$repository);
        $request = new Request();
        $request->attributes->set('start_date', '2015-06-09');
        $request->attributes->set('end_date', '2017-06-09');
        $expectedForFirstTest = 628.88235294118;
        $expectedForSecondTest = 653;

        $this->assertEquals($expectedForFirstTest, $class->getPrice($request, '5714'));
        $this->assertEquals($expectedForSecondTest, $class->getPrice($request, '1'));
    }

    public function testIcanNotGetAveragePriceFromInvalidZipCode()
    {
        $class = new GetFioulPrice(self::$repository);
        $request = new Request();
        $request->attributes->set('start_date', '2015-06-09');
        $request->attributes->set('end_date', '2017-06-09');

        $this->assertNull($class->getPrice($request, '0'));
    }
}