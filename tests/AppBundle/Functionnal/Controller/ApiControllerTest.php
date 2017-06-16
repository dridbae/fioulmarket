<?php

namespace Tests\Functionnal\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public $repository;

    public function setUp()
    {
        self::bootKernel();
        //self::runCommand('import:csv web/import/prices.csv');

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testZipCodesListResponseStatusCode()
    {
        $client = static::createClient();

        $client->request('GET', '/zipCodes');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAveragePriceResponseStatusCode()
    {
        $client = static::createClient();

        $client->request('GET', '/zipCodes/1/get_average_price?start_date=2015-07-06&end_date=2015-08-06');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/zipCodes/1/get_average_price');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $client->request('GET', '/zipCodes/0/get_average_price?start_date=2015-07-08&end_date=2016-07-09');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testAveragePriceResponse()
    {
        $client = static::createClient();

        $expected = json_encode('no value found for this zipCode and Dates');

        $client->request('GET', '/zipCodes/0/get_average_price?start_date=2015-07-08&end_date=2016-07-09');

        $this->assertEquals($expected, $client->getResponse()->getContent());

        $expected = json_encode('missing or invalid parameters [start_date] [end_date]');

        $client->request('GET', '/zipCodes/1/get_average_price');

        $this->assertEquals($expected, $client->getResponse()->getContent());

        $expected = json_encode(["averagePrice" => 649.3333333333334]);

        $client->request('GET', '/zipCodes/1/get_average_price?start_date=2015-07-06&end_date=2015-08-06');

        $this->assertEquals($expected, $client->getResponse()->getContent());
    }

    public function testPricesResponse()
    {
        $client = static::createClient();

        $expected = json_encode('no values found for this zipCode and Dates');

        $client->request('GET', '/zipCodes/0/get_prices?start_date=2015-07-08&end_date=2016-07-09');

        $this->assertEquals($expected, $client->getResponse()->getContent());

        $expected = json_encode('missing or invalid parameters [start_date] [end_date]');

        $client->request('GET', '/zipCodes/1/get_prices');

        $this->assertEquals($expected, $client->getResponse()->getContent());

        $expected = file_get_contents(__DIR__.'/../../fixtures/get_prices_1.json');

        $client->request('GET', '/zipCodes/1/get_prices?start_date=2015-07-06&end_date=2015-08-06');

        $this->assertEquals($expected, $client->getResponse()->getContent());
    }
}
