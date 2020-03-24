<?php

namespace Curl;

/**
 * An object-oriented wrapper of the PHP cURL extension.
 *
 * This library requires to have the php cURL extensions installed:
 * https://php.net/manual/curl.setup.php
 *
 * Example of making a get request with parameters:
 *
 * ```php
 * $curl = new Curl\Curl();
 * $curl->get('http://www.example.com/search', array(
 *     'q' => 'keyword',
 * ));
 * ```
 *
 * Example post request with post data:
 *
 * ```php
 * $curl = new Curl\Curl();
 * $curl->post('http://www.example.com/login/', array(
 *     'username' => 'myusername',
 *     'password' => 'mypassword',
 * ));
 * ```
 *
 * @see https://php.net/manual/curl.setup.php
 */
class Curl
{
    // The HTTP authentication method(s) to use.

    /**
     * @var string Type AUTH_BASIC
     */
    const AUTH_BASIC = CURLAUTH_BASIC;

    /**
     * @var string Type AUTH_DIGEST
     */
    const AUTH_DIGEST = CURLAUTH_DIGEST;

    /**
     * @var string Type AUTH_GSSNEGOTIATE
     */
    const AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;

    /**
     * @var string Type AUTH_NTLM
     */
    const AUTH_NTLM = CURLAUTH_NTLM;

    /**
     * @var string Type AUTH_ANY
     */
    const AUTH_ANY = CURLAUTH_ANY;

    /**
     * @var string Type AUTH_ANYSAFE
     */
    const AUTH_ANYSAFE = CURLAUTH_ANYSAFE;

    /**
     * @var string The user agent name which is set when making a request
     */
    const USER_AGENT = 'PHP Curl/1.5 (+https://github.com/mod-php/curl)';

    private $_cookies = array();

    private $_headers = array();

    /**
     * @var resource Contains the curl resource created by `curl_init()` function
     */
    public $curl;

    /**
     * @var booelan Whether an error occured or not
     */
    public $error = false;

    /**
     * @var int Contains the error code of the curren request, 0 means no error happend
     */
    public $error_code = 0;

    /**
     * @var string If the curl request failed, the error message is contained
     */
    public $error_message = null;

    /**
     * @var booelan Whether an error occured or not
     */
    public $curl_error = false;

    /**
     * @var int Contains the error code of the curren request, 0 means no error happend
     */
    public $curl_error_code = 0;

    /**
     * @var string If the curl request failed, the error message is contained
     */
    public $curl_error_message = null;

    /**
     * @var booelan Whether an error occured or not
     */
    public $http_error = false;

    /**
     * @var int Contains the error code of the curren request, 0 means no error happend
     */
    public $http_status_code = 0;

    /**
     * @var string If the curl request failed, the error message is contained
     */
    public $http_error_message = null;

    /**
     * @var string|array TBD (ensure type) Contains the request header informations
     */
    public $request_headers = null;

    /**
     * @var string|array TBD (ensure type) Contains the response header informations
     */
    public $response_headers = null;

    /**
     * @var string Contains the response from the curl request
     */
    public $response = null;

    /**
     * Constructor ensures the available curl extension is loaded.
     *
     * @throws \ErrorException
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('The cURL extensions is not loaded, make sure you have installed the cURL extension: https://php.net/manual/curl.setup.php');
        }

        $this->init();
    }

    // private methods

    /**
     * Initializer for the curl resource.
     *
     * Is called by the __construct() of the class or when the curl request is reseted.
     */
    private function init()
    {
        $this->curl = curl_init();
        $this->setUserAgent(self::USER_AGENT);
        $this->setOpt(CURLINFO_HEADER_OUT, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    // protected methods

    /**
     * Execute the curl request based on the respectiv settings.
     *
     * @return int Returns the error code for the current curl request
     */
    protected function exec()
    {
        $this->response = curl_exec($this->curl);
        $this->curl_error_code = curl_errno($this->curl);
        $this->curl_error_message = curl_error($this->curl);
        $this->curl_error = !($this->curl_error_code === 0);
        $this->http_status_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->http_error = in_array(floor($this->http_status_code / 100), array(4, 5));
        $this->error = $this->curl_error || $this->http_error;
        $this->error_code = $this->error ? ($this->curl_error ? $this->curl_error_code : $this->http_status_code) : 0;

        $this->request_headers = preg_split('/\r\n/', curl_getinfo($this->curl, CURLINFO_HEADER_OUT), null, PREG_SPLIT_NO_EMPTY);
        $this->response_headers = '';
        if (!(strpos($this->response, "\r\n\r\n") === false)) {
            list($response_header, $this->response) = explode("\r\n\r\n", $this->response, 2);
            while (strtolower(trim($response_header)) === 'http/1.1 100 continue') {
                list($response_header, $this->response) = explode("\r\n\r\n", $this->response, 2);
            }
            $this->response_headers = preg_split('/\r\n/', $response_header, null, PREG_SPLIT_NO_EMPTY);
        }

        $this->http_error_message = $this->error ? (isset($this->response_headers['0']) ? $this->response_headers['0'] : '') : '';
        $this->error_message = $this->curl_error ? $this->curl_error_message : $this->http_error_message;

        return $this->error_code;
    }

    /**
     * @param array|object|string $data
     */
    protected function preparePayload($data)
    {
        $this->setOpt(CURLOPT_POST, true);

        if (is_array($data) || is_object($data)) {
            $data = http_build_query($data);
        }

        $this->setOpt(CURLOPT_POSTFIELDS, $data);
    }

    /**
     * Set auth options for the current request.
     *
     * Available auth types are:
     *
     * + self::AUTH_BASIC
     * + self::AUTH_DIGEST
     * + self::AUTH_GSSNEGOTIATE
     * + self::AUTH_NTLM
     * + self::AUTH_ANY
     * + self::AUTH_ANYSAFE
     *
     * @param int $httpauth The type of authentication
     */
    protected function setHttpAuth($httpauth)
    {
        $this->setOpt(CURLOPT_HTTPAUTH, $httpauth);
    }

    // public methods

    /**
     * @deprecated calling exec() directly is discouraged
     */
    public function _exec()
    {
        return $this->exec();
    }

    // functions

    /**
     * Make a get request with optional data.
     *
     * The get request has no body data, the data will be correctly added to the $url with the http_build_query() method.
     *
     * @param string $url  The url to make the get request for
     * @param array  $data Optional arguments who are part of the url
     */
    public function get($url, $data = array())
    {
        if (count($data) > 0) {
            $this->setOpt(CURLOPT_URL, $url.'?'.http_build_query($data));
        } else {
            $this->setOpt(CURLOPT_URL, $url);
        }
        $this->setOpt(CURLOPT_HTTPGET, true);
        $this->exec();
    }

    /**
     * Make a post request with optional post data.
     *
     * @param string $url  The url to make the get request
     * @param array  $data Post data to pass to the url
     */
    public function post($url, $data = array())
    {
        $this->setOpt(CURLOPT_URL, $url);
        $this->preparePayload($data);
        $this->exec();
    }

    /**
     * Make a put request with optional data.
     *
     * The put request data can be either sent via payload or as get paramters of the string.
     *
     * @param string $url     The url to make the get request
     * @param array  $data    Optional data to pass to the $url
     * @param bool   $payload Whether the data should be transmitted trough payload or as get parameters of the string
     */
    public function put($url, $data = array(), $payload = false)
    {
        if ($payload === false) {
            $url .= '?'.http_build_query($data);
        } else {
            $this->preparePayload($data);
        }

        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->exec();
    }

    /**
     * Make a patch request with optional data.
     *
     * The patch request data can be either sent via payload or as get paramters of the string.
     *
     * @param string $url     The url to make the get request
     * @param array  $data    Optional data to pass to the $url
     * @param bool   $payload Whether the data should be transmitted trough payload or as get parameters of the string
     */
    public function patch($url, $data = array(), $payload = false)
    {
        if ($payload === false) {
            $url .= '?'.http_build_query($data);
        } else {
            $this->preparePayload($data);
        }

        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->exec();
    }

    /**
     * Make a delete request with optional data.
     *
     * @param string $url     The url to make the delete request
     * @param array  $data    Optional data to pass to the $url
     * @param bool   $payload Whether the data should be transmitted trough payload or as get parameters of the string
     */
    public function delete($url, $data = array(), $payload = false)
    {
        if ($payload === false) {
            $url .= '?'.http_build_query($data);
        } else {
            $this->preparePayload($data);
        }
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->exec();
    }

    // setters

    /**
     * Pass basic auth data.
     *
     * If the the rquested url is secured by an httaccess basic auth mechanism you can use this method to provided the auth data.
     *
     * ```php
     * $curl = new Curl();
     * $curl->setBasicAuthentication('john', 'doe');
     * $curl->get('http://example.com/secure.php');
     * ```
     *
     * @param string $username The username for the authentification
     * @param string $password The password for the given username for the authentification
     */
    public function setBasicAuthentication($username, $password)
    {
        $this->setHttpAuth(self::AUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username.':'.$password);
    }

    /**
     * Provide optional header informations.
     *
     * In order to pass optional headers by key value pairing:
     *
     * ```php
     * $curl = new Curl();
     * $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
     * $curl->get('http://example.com/request.php');
     * ```
     *
     * @param string $key   The header key
     * @param string $value The value for the given header key
     */
    public function setHeader($key, $value)
    {
        $this->_headers[$key] = $key.': '.$value;
        $this->setOpt(CURLOPT_HTTPHEADER, array_values($this->_headers));
    }

    /**
     * Provide a User Agent.
     *
     * In order to provide you cusomtized user agent name you can use this method.
     *
     * ```php
     * $curl = new Curl();
     * $curl->setUserAgent('My John Doe Agent 1.0');
     * $curl->get('http://example.com/request.php');
     * ```
     *
     * @param string $useragent The name of the user agent to set for the current request
     */
    public function setUserAgent($useragent)
    {
        $this->setOpt(CURLOPT_USERAGENT, $useragent);
    }

    /**
     * @deprecated Call setReferer() instead
     */
    public function setReferrer($referrer)
    {
        $this->setReferer($referrer);
    }

    /**
     * Set the HTTP referer header.
     *
     * The $referer informations can help identify the requested client where the requested was made.
     *
     * @param string $referer An url to pass and will be set as referer header
     */
    public function setReferer($referer)
    {
        $this->setOpt(CURLOPT_REFERER, $referer);
    }

    /**
     * Set contents of HTTP Cookie header.
     *
     * @param string $key   The name of the cookie
     * @param string $value The value for the provided cookie name
     */
    public function setCookie($key, $value)
    {
        $this->_cookies[$key] = $value;
        $this->setOpt(CURLOPT_COOKIE, http_build_query($this->_cookies, '', '; '));
    }

    /**
     * Set customized curl options.
     *
     * To see a full list of options: http://php.net/curl_setopt
     *
     * @see http://php.net/curl_setopt
     *
     * @param int   $option The curl option constante e.g. `CURLOPT_AUTOREFERER`, `CURLOPT_COOKIESESSION`
     * @param mixed $value  The value to pass for the given $option
     */
    public function setOpt($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * Enable verbositiy.
     *
     * @todo As to keep naming convention it should be renamed to `setVerbose()`
     *
     * @param string $on
     */
    public function verbose($on = true)
    {
        $this->setOpt(CURLOPT_VERBOSE, $on);
    }

    /**
     * Reset all curl options.
     *
     * In order to make multiple requests with the same curl object all settings requires to be reset.
     */
    public function reset()
    {
        $this->close();
        $this->_cookies = array();
        $this->_headers = array();
        $this->error = false;
        $this->error_code = 0;
        $this->error_message = null;
        $this->curl_error = false;
        $this->curl_error_code = 0;
        $this->curl_error_message = null;
        $this->http_error = false;
        $this->http_status_code = 0;
        $this->http_error_message = null;
        $this->request_headers = null;
        $this->response_headers = null;
        $this->response = null;
        $this->init();
    }

    /**
     * Closing the current open curl resource.
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Close the connection when the Curl object will be destroyed.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Was an 'info' header returned.
     */
    public function isInfo()
    {
        if ($this->http_status_code >= 100 && $this->http_status_code < 200) {
            return true;
        }
    }

    /**
     * Was an 'OK' response returned.
     */
    public function isSuccess()
    {
        if ($this->http_status_code >= 200 && $this->http_status_code < 300) {
            return true;
        }
    }

    /**
     * Was a 'redirect' returned.
     */
    public function isRedirect()
    {
        if ($this->http_status_code >= 300 && $this->http_status_code < 400) {
            return true;
        }
    }

    /**
     * Was an 'error' returned (client error or server error).
     */
    public function isError()
    {
        if ($this->http_status_code >= 400 && $this->http_status_code < 600) {
            return true;
        }
    }

    /**
     * Was a 'client error' returned.
     */
    public function isClientError()
    {
        if ($this->http_status_code >= 400 && $this->http_status_code < 500) {
            return true;
        }
    }

    /**
     * Was a 'server error' returned.
     */
    public function isServerError()
    {
        if ($this->http_status_code >= 500 && $this->http_status_code < 600) {
            return true;
        }
    }
}
