<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/9
 * Time: 11:53
 */

namespace app\models;

use Curl\Curl;
use yii\helpers\VarDumper;

/**
 * 数据采集
 */
class DataAcquisition
{
    public static function getData($url)
    {
        preg_match("/^https{0,1}:\/\/[\w\.]*\//", $url, $res);
        if (!(is_array($res) && count($res) > 0 && $res[0])) {
            return null;
        }
        $host = $res[0];
        $host = preg_replace("/https{0,1}:\/\//", "", $host);
        $host = preg_replace("/\//", "", $host);
        $res = null;
        if ($host == 'item.taobao.com') {
            $res = self::getTaobaoData($url);
        }
        //VarDumper::dump($res, 3, 1);
    }

    public static function getTaobaoData($url)
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_HEADER, '');
        $curl->get($url);
        $res = mb_convert_encoding($curl->response, 'utf-8', 'gbk');
        $pattern_list = [
            'title' => "/<h3.*>.*<\/h3>/",
        ];
        $data = [];
        foreach ($pattern_list as $key => $pattern) {
            preg_match_all($pattern, $res, $val);
            $data[$key] = $val;
        }

        VarDumper::dump($data, 5, 1);
        //return $res;
    }

    private static function gbkToUtf8($str)
    {
        $pregstr = "/[\x{4e00}-\x{9fa5}]+/u";//UTF-8中文正则
        if (preg_match_all($pregstr, $str, $matchArray)) {//匹配中文，返回数组
            foreach ($matchArray[0] as $key => $val) {
                $url = str_replace($val, urlencode($val), $str);//将转译替换中文
            }
            if (strpos($str, ' ')) {//若存在空格
                $url = str_replace(' ', '%20', $str);
            }
        }
        return $str;
    }
}
