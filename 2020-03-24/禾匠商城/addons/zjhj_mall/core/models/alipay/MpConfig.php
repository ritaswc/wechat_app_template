<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/8/3 11:49
 */

namespace app\models\alipay;

use Alipay\Key\AlipayKeyPair;
use app\models\Option;
use app\modules\mch\models\MchModel;
use Alipay\Exception\AlipayException;
use Alipay\AlipayCurlRequester;

class MpConfig extends MchModel
{
    public $store_id;

    public $app_id;
    public $alipay_public_key;
    public $app_public_key;
    public $app_private_key;
    public $cs_tnt_inst_id;
    public $cs_scene;

    const OPTION_KEY = 'alipay_mp_config';

    public function rules()
    {
        return [
            [['app_id', 'alipay_public_key', 'app_public_key', 'app_private_key', 'cs_tnt_inst_id', 'cs_scene'], 'trim'],
            [['app_id', 'alipay_public_key', 'app_private_key'], 'required'],
            ['alipay_public_key', function ($attr) {
                // key自动添加 -----BEGIN PUBLIC KEY-----&&-----END PUBLIC KEY-----&&换行符

                $begin_str = '-----BEGIN PUBLIC KEY-----';
                $end_str = '-----END PUBLIC KEY-----';
                $key = $this->alipay_public_key;

                $key = $this->pregReplaceAll('/---.*---/', '', $key);
                $key = trim($key);
                $key = str_replace("\n", '', $key);
                $key = str_replace("\r\n", '', $key);
                $key = str_replace("\r", '', $key);
                $key = wordwrap($key, 64, "\r\n", true);

                if (mb_stripos($key, $begin_str) === false) {
                    $key = $begin_str . "\r\n" . $key;
                }
                if (mb_stripos($key, $end_str) === false) {
                    $key = $key . "\r\n" . $end_str;
                }
                $this->alipay_public_key = $key;
            }],
            ['app_private_key', function ($attr) {
                // key自动添加 -----BEGIN RSA PRIVATE KEY-----&&-----END RSA PRIVATE KEY-----&&换行符
                $begin_str = '-----BEGIN RSA PRIVATE KEY-----';
                $end_str = '-----END RSA PRIVATE KEY-----';
                $key = $this->app_private_key;

                $key = $this->pregReplaceAll('/---.*---/', '', $key);
                $key = trim($key);
                $key = str_replace("\n", '', $key);
                $key = str_replace("\r\n", '', $key);
                $key = str_replace("\r", '', $key);
                $key = wordwrap($key, 64, "\r\n", true);

                if (mb_stripos($key, $begin_str) === false) {
                    $key = $begin_str . "\r\n" . $key;
                }
                if (mb_stripos($key, $end_str) === false) {
                    $key = $key . "\r\n" . $end_str;
                }
                $this->app_private_key = $key;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => '小程序AppID',
            'alipay_public_key' => '支付宝公钥',
            'app_public_key' => '应用公钥',
            'app_private_key' => '应用私钥',
            'cs_tnt_inst_id' => '云客服TntInstId',
            'cs_scene' => '云客服Scene',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $data = $this->attributes;
        unset($data['store_id']);
        Option::set(self::OPTION_KEY, $data, $this->store_id);
        return [
            'code' => 0,
            'msg' => '保存成功。',
        ];
    }

    /**
     * 根据 Store Id 获取其配置实例
     *
     * @param string|int $storeId
     * @return static
     */
    public static function get($storeId)
    {
        $instance = new static();
        $instance->store_id = $storeId;

        $data = Option::get(self::OPTION_KEY, $storeId);
        if ($data != null) {
            $instance->attributes = (array)$data;
        }

        return $instance;
    }

    /**
     * 返回支付宝 AopClient
     *
     * @return \Alipay\AopClient
     */
    public function getClient()
    {
        if ($this->app_id == null) {
            throw new \InvalidArgumentException('支付宝小程序 appid 为空，请检查是否配置支付宝小程序');
        }
        try {
            $kp = AlipayKeyPair::create($this->app_private_key, $this->alipay_public_key);
        } catch (AlipayException $ex) {
            throw new \InvalidArgumentException('支付宝小程序密钥异常，请检查是否配置支付宝小程序');
        }
        $requester = new AlipayCurlRequester([
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
        ]);
        return new \Alipay\AopClient($this->app_id, $kp, null, $requester);
    }

    private function pregReplaceAll($find, $replacement, $s)
    {
        while (preg_match($find, $s)) {
            $s = preg_replace($find, $replacement, $s);
        }
        return $s;
    }
}
