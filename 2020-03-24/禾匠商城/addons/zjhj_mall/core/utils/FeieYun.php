<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11
 * Time: 11:56
 */

namespace app\utils;

class FeieYun
{
    // Request vars
    public $host;
    public $port;
    public $path;
    public $method;
    public $postdata = '';
    public $cookies = array();
    public $referer;
    public $accept = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,image/jpeg,image/gif,*/*';
    public $accept_encoding = 'gzip';
    public $accept_language = 'en-us';
    public $user_agent = 'Incutio HttpClient v0.9';
    public $timeout = 20;
    public $use_gzip = true;
    public $persist_cookies = true;
    public $persist_referers = true;
    public $debug = false;
    public $handle_redirects = true;
    public $max_redirects = 5;
    public $headers_only = false;
    public $username;
    public $password;
    public $status;
    public $headers = array();
    public $content = '';
    public $errormsg;
    public $redirect_count = 0;
    public $cookie_host = '';

    public function __construct($host, $port = 80)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function get($path, $data = false)
    {
        $this->path = $path;
        $this->method = 'GET';
        if ($data) {
            $this->path .= '?' . $this->buildQueryString($data);
        }
        return $this->doRequest();
    }

    public function post($path, $data)
    {
        $this->path = $path;
        $this->method = 'POST';
        $this->postdata = $this->buildQueryString($data);
        return $this->doRequest();
    }

    public function buildQueryString($data)
    {
        $querystring = '';
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $val2) {
                        $querystring .= urlencode($key) . '=' . urlencode($val2) . '&';
                    }
                } else {
                    $querystring .= urlencode($key) . '=' . urlencode($val) . '&';
                }
            }
            $querystring = substr($querystring, 0, -1); // Eliminate unnecessary &
        } else {
            $querystring = $data;
        }
        return $querystring;
    }

    public function doRequest()
    {
        if (!$fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout)) {
            switch ($errno) {
                case -3:
                    $this->errormsg = 'Socket creation failed (-3)';
                    break;
                case -4:
                    $this->errormsg = 'DNS lookup failure (-4)';
                    break;
                case -5:
                    $this->errormsg = 'Connection refused or timed out (-5)';
                    break;
                default:
                    $this->errormsg = 'Connection failed (' . $errno . ')';
                    $this->errormsg .= ' ' . $errstr;
            }
            $this->debug($this->errormsg);
            return false;
        }
        socket_set_timeout($fp, $this->timeout);
        $request = $this->buildRequest();
        $this->debug('Request', $request);
        fwrite($fp, $request);
        $this->headers = array();
        $this->content = '';
        $this->errormsg = '';
        $inHeaders = true;
        $atStart = true;
        while (!feof($fp)) {
            $line = fgets($fp, 4096);
            if ($atStart) {
                $atStart = false;
                if (!preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $line, $m)) {
                    $this->errormsg = "Status code line invalid: " . htmlentities($line);
                    $this->debug($this->errormsg);
                    return false;
                }
                $http_version = $m[1];
                $this->status = $m[2];
                $status_string = $m[3];
                $this->debug(trim($line));
                continue;
            }
            if ($inHeaders) {
                if (trim($line) == '') {
                    $inHeaders = false;
                    $this->debug('Received Headers', $this->headers);
                    if ($this->headers_only) {
                        break;
                    }
                    continue;
                }
                if (!preg_match('/([^:]+):\\s*(.*)/', $line, $m)) {
                    continue;
                }
                $key = strtolower(trim($m[1]));
                $val = trim($m[2]);
                if (isset($this->headers[$key])) {
                    if (is_array($this->headers[$key])) {
                        $this->headers[$key][] = $val;
                    } else {
                        $this->headers[$key] = array($this->headers[$key], $val);
                    }
                } else {
                    $this->headers[$key] = $val;
                }
                continue;
            }
            $this->content .= $line;
        }
        fclose($fp);
        if (isset($this->headers['content-encoding']) && $this->headers['content-encoding'] == 'gzip') {
            $this->debug('Content is gzip encoded, unzipping it');
            $this->content = substr($this->content, 10);
            $this->content = gzinflate($this->content);
        }
        if ($this->persist_cookies && isset($this->headers['set-cookie']) && $this->host == $this->cookie_host) {
            $cookies = $this->headers['set-cookie'];
            if (!is_array($cookies)) {
                $cookies = array($cookies);
            }
            foreach ($cookies as $cookie) {
                if (preg_match('/([^=]+)=([^;]+);/', $cookie, $m)) {
                    $this->cookies[$m[1]] = $m[2];
                }
            }
            $this->cookie_host = $this->host;
        }
        if ($this->persist_referers) {
            $this->debug('Persisting referer: ' . $this->getRequestURL());
            $this->referer = $this->getRequestURL();
        }
        if ($this->handle_redirects) {
            if (++$this->redirect_count >= $this->max_redirects) {
                $this->errormsg = 'Number of redirects exceeded maximum (' . $this->max_redirects . ')';
                $this->debug($this->errormsg);
                $this->redirect_count = 0;
                return false;
            }
            $location = isset($this->headers['location']) ? $this->headers['location'] : '';
            $uri = isset($this->headers['uri']) ? $this->headers['uri'] : '';
            if ($location || $uri) {
                $url = parse_url($location . $uri);
                return $this->get($url['path']);
            }
        }
        return true;
    }

    public function buildRequest()
    {
        $headers = array();
        $headers[] = "{$this->method} {$this->path} HTTP/1.0";
        $headers[] = "Host: {$this->host}";
        $headers[] = "User-Agent: {$this->user_agent}";
        $headers[] = "Accept: {$this->accept}";
        if ($this->use_gzip) {
            $headers[] = "Accept-encoding: {$this->accept_encoding}";
        }
        $headers[] = "Accept-language: {$this->accept_language}";
        if ($this->referer) {
            $headers[] = "Referer: {$this->referer}";
        }
        if ($this->cookies) {
            $cookie = 'Cookie: ';
            foreach ($this->cookies as $key => $value) {
                $cookie .= "$key=$value; ";
            }
            $headers[] = $cookie;
        }
        if ($this->username && $this->password) {
            $headers[] = 'Authorization: BASIC ' . base64_encode($this->username . ':' . $this->password);
        }
        if ($this->postdata) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Content-Length: ' . strlen($this->postdata);
        }
        $request = implode("\r\n", $headers) . "\r\n\r\n" . $this->postdata;
        return $request;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($header)
    {
        $header = strtolower($header);
        if (isset($this->headers[$header])) {
            return $this->headers[$header];
        } else {
            return false;
        }
    }

    public function getError()
    {
        return $this->errormsg;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function getRequestURL()
    {
        $url = 'http://' . $this->host;
        if ($this->port != 80) {
            $url .= ':' . $this->port;
        }
        $url .= $this->path;
        return $url;
    }

    public function setUserAgent($string)
    {
        $this->user_agent = $string;
    }

    public function setAuthorization($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function setCookies($array)
    {
        $this->cookies = $array;
    }

    public function useGzip($boolean)
    {
        $this->use_gzip = $boolean;
    }

    public function setPersistCookies($boolean)
    {
        $this->persist_cookies = $boolean;
    }

    public function setPersistReferers($boolean)
    {
        $this->persist_referers = $boolean;
    }

    public function setHandleRedirects($boolean)
    {
        $this->handle_redirects = $boolean;
    }

    public function setMaxRedirects($num)
    {
        $this->max_redirects = $num;
    }

    public function setHeadersOnly($boolean)
    {
        $this->headers_only = $boolean;
    }

    public function setDebug($boolean)
    {
        $this->debug = $boolean;
    }

    public function quickGet($url)
    {
        $bits = parse_url($url);
        $host = $bits['host'];
        $port = isset($bits['port']) ? $bits['port'] : 80;
        $path = isset($bits['path']) ? $bits['path'] : '/';
        if (isset($bits['query'])) {
            $path .= '?' . $bits['query'];
        }
        $client = new HttpClient($host, $port);
        if (!$client->get($path)) {
            return false;
        } else {
            return $client->getContent();
        }
    }

    public function quickPost($url, $data)
    {
        $bits = parse_url($url);
        $host = $bits['host'];
        $port = isset($bits['port']) ? $bits['port'] : 80;
        $path = isset($bits['path']) ? $bits['path'] : '/';
        $client = new HttpClient($host, $port);
        if (!$client->post($path, $data)) {
            return false;
        } else {
            return $client->getContent();
        }
    }

    public function debug($msg, $object = false)
    {
        if ($this->debug) {
            print '<div style="border: 1px solid red; padding: 0.5em; margin: 0.5em;"><strong>HttpClient Debug:</strong> ' . $msg;
            if ($object) {
                ob_start();
                print_r($object);
                $content = htmlentities(ob_get_contents());
                ob_end_clean();
                print '<pre>' . $content . '</pre>';
            }
            print '</div>';
        }
    }
}
