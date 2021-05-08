<?php

namespace app\hejiang;

class Serializer extends \yii\base\Component
{
    public function __construct()
    {
    }

    public function encode($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function decode($value)
    {
        $res = json_decode($value, true);
        if ($res === null) {
            if (json_last_error() == JSON_ERROR_NONE) {
                return $res;
            }
            if (json_last_error() != JSON_ERROR_SYNTAX) {
                $error = json_last_error_msg();
                throw new \InvalidArgumentException("{$error}: `{$value}` cannot be decoded!");
            }
            $res = unserialize($value);
            if ($res === false) {
                $value = preg_replace_callback(
                    '/s:([0-9]+):\"(.*?)\";/',
                    function ($matches) {
                        return "s:" . strlen($matches[2]) . ':"' . $matches[2] . '";';
                    },
                    $value
                );
                $res = unserialize($value);
                if ($res === false) {
                    throw new \InvalidArgumentException("`{$value}` cannot be unserialized!");
                }
            }
        }
        if (!is_object($res) && !is_array($res)) {
            return $res;
        }
        return new \ArrayObject($res, \ArrayObject::ARRAY_AS_PROPS);
    }
}
