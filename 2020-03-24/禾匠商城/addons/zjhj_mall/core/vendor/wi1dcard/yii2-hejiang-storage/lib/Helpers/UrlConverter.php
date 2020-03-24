<?php

namespace Hejiang\Storage\Helpers;

class UrlConverter
{
    public $replaceUrl;

    public function __construct($replaceUrl = '')
    {
        $this->replaceUrl = $replaceUrl;
    }

    public function __invoke($objectUrl, $saveTo, $driver)
    {
        if ($this->replaceUrl == '') {
            return $objectUrl;
        }
        return static::replaceUrl($objectUrl, $this->replaceUrl);
    }

    public static function replaceUrl($url, $replaceTo)
    {
        $urlParts = parse_url($url);
        $replaceParts = parse_url($replaceTo);
        $finalParts = array_merge($urlParts, $replaceParts);
        return static::buildUrl($finalParts);
    }

    public static function buildUrl($parts)
    {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . (isset($parts['user']) ? "{$parts['user']}" : '') . (isset($parts['pass']) ? ":{$parts['pass']}" : '') . (isset($parts['user']) ? '@' : '') . (isset($parts['host']) ? "{$parts['host']}" : '') . (isset($parts['port']) ? ":{$parts['port']}" : '') . (isset($parts['path']) ? "{$parts['path']}" : '') . (isset($parts['query']) ? "?{$parts['query']}" : '') . (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}
