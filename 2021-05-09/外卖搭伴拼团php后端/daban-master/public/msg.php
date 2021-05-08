<?php
$config = array(
    'appId' => 'wx38002adc825edc81',
    'appSecret' => '9dc5b950c4ec8d8efffa5b7acb40702e',
    'token' => 'daban'
    );



$message = new Message($config);
$message->valid();

$object = $message->getObject();

if($object->MsgType == 'text'){
 $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$message->getAccessToken();
 $data = '{
             "touser":"'.$object->FromUserName.'",
             "msgtype":"text",
             "text":
             {
                  "content":"搭伴拼团客服人员稍后会与您联系，谢谢~！"
             }
         }';
 $message->curl($url,$data);     
}

class Message {

	private static $config;//配置项
	private $object;	

	public function __construct(array $config){
        if(!empty($config)){
            self::$config = $config;
        }
        $this->object = $this->parsePostRequestData();
    }

    //验证消息的确来自微信服务器
    public function valid(){
        if(isset($_GET['echostr'])){
            $signature = $_GET['signature'];
            $timestamp = $_GET['timestamp'];
            $nonce = $_GET['nonce'];
            $token = self::$config['token'];
            $tmpArr = array($token,$timestamp,$nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if($tmpStr == $signature){
                echo $_GET['echostr'];
                exit;
            }
        }
    }

    //获取并解析用户传过来的数据
    private function parsePostRequestData(){
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) && !empty($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        if(!empty($postStr)){
            return simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        }
    }

    public function getObject(){
        return $this->object;
    }

    //获取access_token
    public function getAccessToken(){
        //缓存access_token
        $cachename = md5(self::$config['appId'].self::$config['appSecret']);
         $file = __DIR__.'/cache/'.$cachename.'.php';

        if(is_file($file) && filemtime($file) + 7000 > time()){
            $data = include $file;
        }else{
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$config['appId'].'&secret='.self::$config['appSecret'];
            $data = $this->curl($url);
            if(isset($data['errcode'])){
                return false;
            }
            /*file_put_contents($file,"<?php return \r\n".var_export($data,true).";\r\n?>");*/
        }
        return $data['access_token'];
    }

    /**
     * curl请求 $field不为空时为post请求
     * @param $url 请求地址
     * @param array $fields post数据
     * @return mixed|string 返回数据
     */
    public function curl($url, $fields){
        $ch = curl_init(); //curl初始化
        curl_setopt($ch,CURLOPT_URL,$url); //设置请求地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//数据返回后不要直接显示
        //禁止证书校验
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        if($fields){
            curl_setopt($ch,CURLOPT_TIMEOUT,30);//响应时间为30秒
            curl_setopt($ch,CURLOPT_POST,1);//设为post请求
            curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);//post数据
        }
        $data = '';
        if(curl_exec($ch)){
            if(curl_errno($ch)){
                echo 'Curl error: ' . curl_error($ch);
                exit;
            }
            //发送成功，获取数据
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        $curlData = json_decode($data,true);
        if(is_array($curlData)){
            return $curlData;
        }else{
            return $data;
        }
    }
}




