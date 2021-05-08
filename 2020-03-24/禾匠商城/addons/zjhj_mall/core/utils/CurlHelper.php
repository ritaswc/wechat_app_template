<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/24
 * Time: 12:34
 */

namespace app\utils;

class CurlHelper
{
    public static $auto_redirect = true;
    public static $curl_info;
    public static $curl_errno;
    public static $curl_error;
    public static $response;

    public static function get($url, $data = [])
    {
        if (is_array($data) || is_object($data)) {
            $url = trim($url, '&');
            $url = trim($url, '?');
            if (strpos($url, '?') !== false) {
                $url = $url . '&' . http_build_query($data);
            } else {
                $url = $url . '?' . http_build_query($data);
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        self::$response = curl_exec($ch);
        self::$curl_errno = curl_errno($ch);
        self::$curl_error = curl_error($ch);
        self::$curl_info = curl_getinfo($ch);
        curl_close($ch);
        if (self::$auto_redirect && in_array(self::$curl_info['http_code'], [301, 302])) {
            return self::get(self::$curl_info['redirect_url'], $data);
        }
        return self::$response;
    }

    public static function post($url, $data = [])
    {
        if (is_array($data) || is_object($data)) {
            $data = http_build_query($data);
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        self::$response = curl_exec($ch);
        self::$curl_errno = curl_errno($ch);
        self::$curl_error = curl_error($ch);
        self::$curl_info = curl_getinfo($ch);
        curl_close($ch);
        if (self::$auto_redirect && in_array(self::$curl_info['http_code'], [301, 302])) {
            return self::post(self::$curl_info['redirect_url'], $data);
        }
        return self::$response;
    }


    /**
     * 下载文件,GET方式提交
     * @param $url
     * @param $save_file string 文件保存路径
     * @param array $data GET方式提交数据
     * @return mixed
     */
    public static function download($url, $save_file, $data = [])
    {
        if ($save_file === null || $save_file === '') {
            return false;
        }
        $fp_output = fopen($save_file, 'w');
        if (is_array($data) || is_object($data)) {
            $url = trim($url, '&');
            $url = trim($url, '?');
            if (strpos($url, '?') !== false) {
                $url = $url . '&' . http_build_query($data);
            } else {
                $url = $url . '?' . http_build_query($data);
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FILE, $fp_output);
        self::$response = curl_exec($ch);
        self::$curl_errno = curl_errno($ch);
        self::$curl_error = curl_error($ch);
        self::$curl_info = curl_getinfo($ch);
        curl_close($ch);
        if (self::$auto_redirect && in_array(self::$curl_info['http_code'], [301, 302])) {
            return self::download(self::$curl_info['redirect_url'], $save_file, $data);
        }
        return self::$response;
    }

    /**
     * POST方式提交;
     * 兼容PHP5.5要求使用CURLFile上传文件,内部自动处理@开头标记的文件
     * @param $url
     * @param array $data ['mydata'=>'123','image1'=>'@/path/to/file','image2'=>'@/path/to/file']
     * @return mixed
     */
    public static function upload($url, $data = [])
    {
        if (function_exists('curl_file_create')) {
            foreach ($data as $k => $v) {
                if (strpos($v, '@') === 0) {
                    $data[$k] = curl_file_create(trim($v, '@'));
                }
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        self::$response = curl_exec($ch);
        self::$curl_errno = curl_errno($ch);
        self::$curl_error = curl_error($ch);
        self::$curl_info = curl_getinfo($ch);
        curl_close($ch);
        if (self::$auto_redirect && in_array(self::$curl_info['http_code'], [301, 302])) {
            return self::upload(self::$curl_info['redirect_url'], $data);
        }
        return self::$response;
    }

    /**
     * 根据路径或网址格式获取文件名
     * @param string $str 例：http://a.com/filename.txt或/home/filename.txt
     * @return mixed|null
     */
    public static function getFileName($str)
    {
        if ($str === '' || $str === null) {
            return null;
        }
        $names = mb_split('/', $str);
        if (!is_array($names)) {
            return null;
        }
        return $names[count($names) - 1];
    }

    /**
     * 根据文件名或文件路径获取文件后缀
     * @param $str
     * @return mixed|null
     */
    public static function getFileExtension($str)
    {
        $str = self::getFileName($str);
        if ($str === '' || $str === null) {
            return null;
        }
        $names = mb_split('\.', $str);
        if (!is_array($names)) {
            return null;
        }
        if (count($names) == 1) {
            return '';
        }
        return $names[count($names) - 1];
    }
}
