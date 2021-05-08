<?php

namespace app\utils;

use Alipay\AlipayRequestFactory;
use Curl\Curl;
use luweiss\wechat\Wechat;
use app\models\alipay\MpConfig;
use app\modules\api\models\ApiModel;

class GenerateShareQrcode
{

    /**
     * @param $storeId integer 商城ID
     * @param $scene string 二维码参数
     * @param int $width 二维码大小
     * @param null $page 跳转页面
     * @param int $platform 小程序类型 0--微信 1--支付宝
     */
    public static function getQrcode($storeId, $scene, $width = 430, $page = null)
    {
        if ($page == null) {
            $page = 'pages/index/index';
        }
        $model = new GenerateShareQrcode();
        if (\Yii::$app->fromAlipayApp()) {
            return $model->alipay($scene, $storeId, $page, '二维码');
        } else {
            return $model->wechat($scene, $width, $page);
        }
    }


    public function wechat($scene, $width = 430, $page = null)
    {
        $wechat = ApiModel::getWechat();
        $access_token = $wechat->getAccessToken();
        if (!$access_token) {
            return [
                'code' => 1,
                'msg' => $wechat->errMsg,
            ];
        }
        $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $data = [
            'scene' => $scene,
            'width' => $width,
        ];
        if ($page) {
            $data['page'] = $page;
        }
        $data = json_encode($data);
        \Yii::trace("GET WXAPP QRCODE:" . $data);
        $curl->post($api, $data);
        if (in_array('Content-Type: image/jpeg', $curl->response_headers)) {
            //返回图片
            return [
                'code' => 0,
                'file_path' => $this->saveTempImageByContent($curl->response),
            ];
        } else {
            //返回文字
            $res = json_decode($curl->response, true);
            return [
                'code' => 1,
                'msg' => $res['errmsg'],
            ];
        }
    }

    /**
     * 小程序生成推广二维码接口
     *
     * @see https://docs.open.alipay.com/api_5/alipay.open.app.qrcode.create
     */
    public function alipay($scene, $storeId, $page = null, $describe = '')
    {

        try {
            $aop = ApiModel::getAlipay($storeId);

            $request = AlipayRequestFactory::create('alipay.open.app.qrcode.create', [
                'biz_content' => [
                    'url_param' => $page,
                    'query_param' => $scene,
                    'describe' => $describe,
                ],
            ]);
            $data = $aop->execute($request)->getData();
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
        $curl = new Curl();
        $curl->setopt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($data['qr_code_url']);
        $image = $curl->response;
        $path = $this->saveTempImageByContent($image);

        return [
            'code' => 0,
            'file_path' => $path,
        ];
    }

    //保存图片内容到临时文件
    private function saveTempImageByContent($content)
    {
        $save_path = \Yii::$app->runtimePath . '/image/' . md5(base64_encode($content)) . '.jpg';
        if(!is_dir(\Yii::$app->runtimePath . '/image')) {
            mkdir(\Yii::$app->runtimePath . '/image');
        }
        $fp = fopen($save_path, 'w');
        fwrite($fp, $content);
        fclose($fp);
        return $save_path;
    }
}
