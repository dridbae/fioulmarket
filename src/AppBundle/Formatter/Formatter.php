<?php

namespace AppBundle\Formatter;

/**
 * Interface Formatter
 * @package AppBundle\Formatter
 */
interface Formatter
{
    /**
     * @param $results
     * @return mixed
     */
    public function format($results);
}