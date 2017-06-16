<?php

namespace AppBundle\Services;

/**
 * @codeCoverageIgnore
 */
/**
 * Class CsvToArray
 * @package AppBundle\Services
 */
class CsvToArray
{
    /**
     * @param $filename
     * @return array|bool
     */
    public function convert($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $csv = array_map('str_getcsv', file($filename));

        return $csv;
    }
}
