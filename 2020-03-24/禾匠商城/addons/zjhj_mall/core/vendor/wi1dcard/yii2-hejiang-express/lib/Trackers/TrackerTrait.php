<?php

namespace Hejiang\Express\Trackers;

use Curl\Curl;
use Hejiang\Express\Exceptions\TrackingException;

trait TrackerTrait
{
    /**
     * Run HTTP GET method
     *
     * @param string $apiUrl
     * @return Curl
     */
    protected static function httpGet($apiUrl)
    {
        $curl = new Curl();
        $curl->get($apiUrl);
        return $curl;
    }

    /**
     * Parse JSON response
     *
     * @param Curl $curl
     * @return \stdClass
     */
    protected static function getJsonResponse(Curl $curl)
    {
        $responseRaw = $curl->response;
        $response = json_decode($responseRaw);
        if ($response == false) {
            throw new TrackingException('Response data cannot be decoded as json.', $responseRaw);
        }
        return $response;
    }

    public static function isSupported($expressName)
    {
        $list = static::getSupportedExpresses();
        return isset($list[$expressName]);
    }

    public static function getExpressCode($expressName)
    {
        if (static::isSupported($expressName)) {
            $list = static::getSupportedExpresses();
            return $list[$expressName];
        } else {
            throw new TrackingException("Unsupported express name: {$expressName}");
        }
    }
}
