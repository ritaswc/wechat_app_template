<?php
namespace Flc\Alidayu;

/**
 * 阿里大于 - 辅助类
 *
 * @author Flc <2016年9月19日 21:01:49>
 * @link   http://flc.ren
 */
class Support
{
    /**
     * 格式化数组为json字符串（避免数字等不符合规格）
     * @param  array $params 数组
     * @return string
     */
    public static function jsonStr($params = [])
    {
        $arr = [];

        array_walk($params, function($value, $key) use (&$arr) {
            $arr[] = "\"{$key}\":\"{$value}\"";
        });

        if (is_array($arr) || count($arr) > 0) {
            return '{' . implode(',', $arr) . '}';
        }

        return '';
    }

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string       
     */
    public static function randStr($len = 6)
    {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }
}
