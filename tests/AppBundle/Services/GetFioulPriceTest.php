<?php

namespace Tests\AppBundle\Services;

use AppBundle\Repository\FioulPriceRepository;
use AppBundle\Services\GetFioulPrice;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GetFioulPriceTest extends TestCase
{
    static $container;

    static $repository;

    private $prophet;

    protected function setup()
    {
          $this->prophet = new \Prophecy\Prophet;
    }

    public function testIcanValidateDates()
    {
        $mockedRepository = $this->prophet->prophesize(FioulPriceRepository::class);
        $repository = $mockedRepository->reveal();

        $class = new GetFioulPrice($repository);
        $request = new Request();
        $request->attributes->set('start_date', '2017-06-09');
        $request->attributes->set('end_date', '2017-06-09');

        $this->assertTrue($class->validateDate($request));
    }

    public function testIcanNotValidateDates()
    {
        $mockedRepository = $this->prophet->prophesize(FioulPriceRepository::class);
        $repository = $mockedRepository->reveal();

        $class = new GetFioulPrice($repository);
        $request = new Request();
        $request->attributes->set('start_date', '2017-06-09');
        $request->attributes->set('end_date', '2017-06-t');

        $this->assertFalse($class->validateDate($request));

        $request->attributes->set('start_date', '2017-06-09');

        $this->assertFalse($class->validateDate($request));
    }

    public function testIcanGetAveragePriceFromValidZipCode()
    {
        $mockedRepository = $this->prophet->prophesize(FioulPriceRepository::class);
        $mockedRepository
            ->retrieveAmountFromZipCodeAndDate('1', '2015-06-09', '2017-06-09')
            ->willReturn(
                json_decode(
                    file_get_contents(
                        __DIR__ . '/../fixtures/get_average_price_1.json'
                    ),
                    true
                )
            );

        $repository = $mockedRepository->reveal();
        $class = new GetFioulPrice($repository);
        $request = new Request();
        $request->attributes->set('start_date', '2015-06-09');
        $request->attributes->set('end_date', '2017-06-09');
        $expectedForFirstTest = 652.42424242424238;

        $this->assertEquals($expectedForFirstTest, $class->getPrice($request, '1'));
    }

    public function testIcanNotGetAveragePriceFromInvalidZipCode()
    {
        $mockedRepository = $this->prophet->prophesize(FioulPriceRepository::class);
        $mockedRepository->retrieveAmountFromZipCodeAndDate('0', '2015-06-09', '2017-06-09')->willReturn([]);
        $repository = $mockedRepository->reveal();
        $class = new GetFioulPrice($repository);
        $request = new Request();
        $request->attributes->set('start_date', '2015-06-09');
        $request->attributes->set('end_date', '2017-06-09');

        $this->assertNull($class->getPrice($request, '0'));
    }

    public function testIcanNotGetPricesFromInvalidZipCode()
    {
        $mockedRepository = $this->prophet->prophesize(FioulPriceRepository::class);
        $mockedRepository->retrieveAmountAndDateFromZipCodeAndDate('0', '2015-06-09', '2017-06-09')->willReturn([]);
        $repository = $mockedRepository->reveal();
        $class = new GetFioulPrice($repository);
        $request = new Request();
        $request->attributes->set('start_date', '2015-06-09');
        $request->attributes->set('end_date', '2017-06-09');

        $this->assertNull($class->getPrices($request, '0'));
    }
}