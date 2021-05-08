<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 9:23
 */

namespace app\utils;

class Yprint
{
    /**
     * 生成签名sign
     * @param  array $params 参数
     * @param  string $apiKey API密钥
     * @param  string $msign 打印机密钥
     * @return   string sign
     */
    public function generateSign($params, $apiKey, $msign)
    {
        //所有请求参数按照字母先后顺序排
        ksort($params);
        //定义字符串开始所包括的字符串
        $stringToBeSigned = $apiKey;
        //把所有参数名和参数值串在一起
        foreach ($params as $k => $v) {
            $stringToBeSigned .= urldecode($k.$v);
        }
        unset($k, $v);
        //定义字符串结尾所包括的字符串
        $stringToBeSigned .= $msign;
        //使用MD5进行加密，再转化成大写
        return strtoupper(md5($stringToBeSigned));
    }
    /**
     * 生成字符串参数
     * @param array $param 参数
     * @return  string        参数字符串
     */
    public function getStr($param)
    {
        $str = '';
        foreach ($param as $key => $value) {
            $str=$str.$key.'='.$value.'&';
        }
        $str = rtrim($str, '&');
        return $str;
    }
    /**
     * 打印接口
     * @param  int $partner     用户ID
     * @param  string $machine_code 打印机终端号
     * @param  string $content      打印内容
     * @param  string $apiKey       API密钥
     * @param  string $msign       打印机密钥
     */
    public function action_print($partner, $machine_code, $content, $apiKey, $msign)
    {
        $param = array(
            "partner"=>$partner,
            'machine_code'=>$machine_code,
            'time'=>time(),
        );
        //获取签名
        $param['sign'] = $this->generateSign($param, $apiKey, $msign);
        $param['content'] = urlencode($content);
        $str = $this->getStr($param);
        return $this->sendCmd('http://open.10ss.net:8888', $str);
    }
    /**
     *  添加打印机
     * @param  int $partner     用户ID1
     * @param  string $machine_code 打印机终端号
     * @param  string $username     用户名
     * @param  string $printname    打印机名称
     * @param  string $mobilephone  打印机卡号
     * @param  string $apiKey       API密钥
     * @param  string $msign       打印机密钥
     */
    public function action_addprint($partner, $machine_code, $username, $printname, $mobilephone, $apiKey, $msign)
    {
        $param = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'username'=>$username,
            'printname'=>$printname,
            'mobilephone'=>$mobilephone,
        );
        $param['sign'] = $this->generateSign($param, $apiKey, $msign);
        $param['msign'] = $msign;
        $str = $this->getStr($param);
        return $this->sendCmd('http://open.10ss.net:8888/addprint.php', $str);
    }
    /**
     * 删除打印机
     * @param  int $partner      用户ID
     * @param  string $machine_code 打印机终端号
     * @param  string $apiKey       API密钥
     * @param  string $msign        打印机密钥
     */
    public function action_removeprinter($partner, $machine_code, $apiKey, $msign)
    {
        $param = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
        );
        $param['sign'] = $this->generateSign($param, $apiKey, $msign);
        $str = $this->getStr($param);
        return $this->sendCmd('http://open.10ss.net:8888/removeprint.php', $str);
    }
    /**
     * 发起请求
     * @param  string $url  请求地址
     * @param  string $data 请求数据包
     * @return   string      请求返回数据
     */
    public function sendCmd($url, $data)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            \Yii::trace('打印出错：'.curl_error($curl));
//            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }
}
