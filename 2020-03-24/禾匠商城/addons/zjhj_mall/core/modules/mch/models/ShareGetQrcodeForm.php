<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/5
 * Time: 9:35
 */

namespace app\modules\mch\models;

use Curl\Curl;

class ShareGetQrcodeForm extends MchModel
{
    public $store_id;
    public $user_id;

    public function rules()
    {
        return [
            [['user_id'],'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $save_root = \Yii::$app->basePath . '/web/temp/';
        if (!is_dir($save_root)) {
            mkdir($save_root);
            file_put_contents($save_root . '.gitignore', "*\r\n!.gitignore");
        }
        $version = hj_core_version();
        $save_name = sha1("v={$version}&store_id={$this->store_id}&user_id={$this->user_id}&type=qrcode") . '.jpg';
        $save_path = $save_root.$save_name;

        $pic_url = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $save_name;

        $wdcp_patch = false;
        $wdcp_patch_file = \Yii::$app->basePath . '/patch/wdcp.json';
        if (file_exists($wdcp_patch_file)) {
            $wdcp_patch = json_decode(file_get_contents($wdcp_patch_file), true);
            if ($wdcp_patch && in_array(\Yii::$app->request->hostName, $wdcp_patch)) {
                $wdcp_patch = true;
            } else {
                $wdcp_patch = false;
            }
        }
        if ($wdcp_patch) {
            $pic_url = str_replace('http://', 'https://', $pic_url);
        }

        if (file_exists($save_root . $save_name)) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url'=>$pic_url . '?v=' . time()
                ]
            ];
        }
        $wechat = $this->getWechat();
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
            'scene' => "{$this->user_id}",
            'width' => "430",
        ];
        $data = json_encode($data);
        \Yii::trace("GET WXAPP QRCODE:" . $data);
        $curl->post($api, $data);
        if (in_array('Content-Type: image/jpeg', $curl->response_headers)) {
            $fp = fopen($save_path, 'w');
            fwrite($fp, $curl->response);
            fclose($fp);

            //返回图片
            return [
                'code' => 0,
                'data'=>[
                    'url' => $pic_url
                ]
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
}
