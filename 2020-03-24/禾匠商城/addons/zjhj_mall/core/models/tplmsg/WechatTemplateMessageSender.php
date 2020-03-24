<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/3 16:26
 */


namespace app\models\tplmsg;

use Curl\Curl;
use luweiss\wechat\Wechat;

class WechatTemplateMessageSender
{
    /** @var Wechat $wechat */
    private $wechat;
    private $curl;

    public function __construct($wechat)
    {
        $this->wechat = $wechat;
    }

    public function send($data)
    {
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $raw = $this->httpPost($api, $data);
        $res = json_decode($raw, true);
        if (!$res) {
            throw new TplmsgException("模板消息发送失败，服务器返回内容出错，服务器返回内容：{$raw}");
        }
        if ($res['errcode'] && $res['errcode'] !== 0) {
            throw new TplmsgException("模板消息发送失败，错误信息：code={$res['errcode']}，msg={$res['errmsg']}，data={$data}");
        }
        return true;
    }


    private function httpGet($url, $data = null)
    {
        $curl = $this->getCurl();
        $curl->get($url, $data);
        if ($curl->error_code) {
            throw new TplmsgException("模板消息发送失败，网络出错：{$curl->error_message}");
        }
        return $curl->response;
    }

    private function httpPost($url, $data = null)
    {
        $curl = $this->getCurl();
        $curl->post($url, $data);
        if ($curl->error_code) {
            throw new TplmsgException("模板消息发送失败，网络出错：{$curl->error_message}");
        }
        return $curl->response;
    }

    private function getCurl()
    {
        if ($this->curl) {
            return $this->curl;
        }
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $this->curl;
    }
}
