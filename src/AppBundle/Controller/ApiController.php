<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * @Route("/zipCodes/{zipCode}/get_average_price", name="get_average_price", requirements={"zip_code": "\d+"})
     */
    public function getAveragePriceAction(Request $request, $zipCode)
    {
        try {
            if ($averagePrice = $this->get('app.services.get_fioul_price')->getPrice($request, $zipCode)) {
                return new JsonResponse(
                    array(
                        'averagePrice' => $averagePrice,
                    )
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), '400');
        }

        return new JsonResponse('no value found for this zipCode and Dates', 404);
    }

    /**
     * @Route("/zipCodes/{zipCode}/get_prices", name="get_prices", requirements={"zip_code": "\d+"})
     */
    public function getPricesAction(Request $request, $zipCode)
    {
        try {
            if ($averagePrice = $this->get('app.services.get_fioul_price')->getPrices($request, $zipCode)) {
                return new JsonResponse(
                    ($averagePrice)
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), '400');
        }

        return new JsonResponse('no values found for this zipCode and Dates', 404);
    }

    /**
     * @Route("/zipCodes", name="zip_codes_list")
     */
    public function zipCodesListAction()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        try {
            $zipCodes = $em->getRepository('AppBundle:FioulPrice')->retrieveZipCodes();
        } catch (\Exception $e) {
            return new JsonResponse('Impossible to connect to database', 500);
        }
        if (count($zipCodes)) {
            return new JsonResponse(array_keys($zipCodes));
        }

        return new JsonResponse(array('no zipCodes found in API'), 404);
    }
}
