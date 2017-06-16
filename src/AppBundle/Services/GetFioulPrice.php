<?php

namespace AppBundle\Services;

use AppBundle\Formatter\Formatter;
use AppBundle\Formatter\PricesAndDateFormatter;
use AppBundle\Repository\FioulPriceRepository;
use AppBundle\Specifications\Domain\IsNotNull;
use AppBundle\Specifications\Domain\IsValidDate;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetFioulPrice
 * @package AppBundle\Services
 */
class GetFioulPrice
{
    /**
     * @var FioulPriceRepository
     */
    protected $repository;

    /**
     * GetFioulPrice constructor.
     * @param FioulPriceRepository $repository
     * @param Formatter $formatter
     */
    public function __construct(FioulPriceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param int $zipCode
     * @return float|int|null
     * @throws \Exception
     */
    public function getPrice(Request $request, int $zipCode)
    {
        if (!$this->validateDate($request)) {
            throw new \Exception('missing or invalid parameters [start_date] [end_date]');
        }

        $start = $request->get('start_date');
        $end = $request->get('end_date');

        $averagePrice = 0;
        $amounts = $this->repository->retrieveAmountFromZipCodeAndDate($zipCode, $start, $end);

        if (!($nbAmounts = count($amounts)) > 0) {
            return null;
        }

        foreach ($amounts as $amount) {
            if (isset($amount['amount'])) {
                $averagePrice += (int) ($amount['amount']);
            }
        }

        return $averagePrice / $nbAmounts;
    }

    /**
     * @param Request $request
     * @param int $zipCode
     * @return float|int|null
     * @throws \Exception
     */
    public function getPrices(Request $request, int $zipCode)
    {
        if (!$this->validateDate($request)) {
            throw new \Exception('missing or invalid parameters [start_date] [end_date]');
        }

        $start = $request->get('start_date');
        $end = $request->get('end_date');

        $results = $this->repository->retrieveAmountAndDateFromZipCodeAndDate($zipCode, $start, $end);

        if (!count($results) > 0) {
            return null;
        }

        $results = (new PricesAndDateFormatter())->format($results);

        return $results;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function validateDate(Request $request)
    {
        $startValidation = (new IsNotNull())
            ->andSpec(
                new IsValidDate()
            )->isSatisfiedBy(
                $request->get('start_date')
            );

        $endValidation = (new IsNotNull())
            ->andSpec(
                new IsValidDate()
            )->isSatisfiedBy(
                $request->get('end_date')
            );

        if (!$startValidation || !$endValidation) {
            return false;
        }

        return true;
    }
}
