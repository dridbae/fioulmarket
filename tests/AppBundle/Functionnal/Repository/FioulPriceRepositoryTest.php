<?php

namespace Tests\Functionnal\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class FioulPriceRepositoryTest extends WebTestCase
{
    protected static $application;

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

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    public function testIcanRetrieveDatasFromDb()
    {
        $datas = $this->em
            ->getRepository('AppBundle:FioulPrice')
            ->findOneByZipCode(1);
        ;

        $this->assertNotNull($datas);
    }
}