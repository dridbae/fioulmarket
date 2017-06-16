<?php

namespace Tests\AppBundle\Services;

use AppBundle\Formatter\PricesAndDateFormatter;
use PHPUnit\Framework\TestCase;

class PricesAndDateFormatterTest extends TestCase
{
    public function testICannotFormatEmptyResults()
    {
        $mockedResults = [];

        $this->assertNull((new PricesAndDateFormatter())->format($mockedResults));
    }

    public function testICannotFormatWrongResults()
    {
        $mockedResults = [['date'=> new \DateTime()], ['amount' => 1]];

        $this->assertEquals($mockedResults, (new PricesAndDateFormatter())->format($mockedResults));
    }

    public function testICannotFormatProperResults()
    {
        $date1 = new \DateTime();
        $date2 = new \DateTime('+1 hour');

        $mockedEntry = [['date'=> $date1, 'amount' => 2], ['amount' => 1, 'date' => $date2]];
        $mockedResults = [['date'=> $date1->format('Y-m-d'), 'amount' => 2], ['amount' => 1, 'date' => $date2->format('Y-m-d')]];

        $this->assertEquals($mockedResults, (new PricesAndDateFormatter())->format($mockedEntry));
    }
}