<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;
use Alipay\Exception\AlipayHttpException;

class AlipayCurlRequester extends AlipayRequester
{
    /**
     * Curl 选项
     *
     * @param array $options
     */
    public $options = [];

    public function __construct($options = [])
    {
        $this->options = $options + [
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
        parent::__construct([$this, 'post']);
    }

    /**
     * 发起 POST 请求
     *
     * @param string $url
     * @param array  $params
     *
     * @return mixed
     */
    public function post($url, $params)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        foreach ($params as &$value) {
            if (is_string($value) && strlen($value) > 0 && $value[0] === '@' && class_exists('CURLFile')) {
                $file = substr($value, 1);
                if (is_file($file)) {
                    $value = new \CURLFile($file);
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new AlipayCurlException(curl_error($ch), curl_errno($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $httpStatusCode) {
            throw new AlipayHttpException($response, $httpStatusCode);
        }

        curl_close($ch);

        return $response;
    }
}
