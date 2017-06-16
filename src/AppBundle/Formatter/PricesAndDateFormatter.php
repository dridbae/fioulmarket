<?php

namespace AppBundle\Formatter;

/**
 * Class PricesAndDateFormatter
 * @package AppBundle\Formatter
 */
class PricesAndDateFormatter implements Formatter
{
    /**
     * @param $results
     * @return null
     */
    public function format($results)
    {
        if (!count($results)) {
            return null;
        }

        foreach ($results as $key => $result) {
            if (isset($result['amount'], $result['date'])) {
                $results[$key]['date'] = $result['date']->format('Y-m-d');
            }
        }

        return $results;
    }
}