<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 16:07
 */

namespace app\modules\api\models;

use app\models\UploadConfig;
use app\models\UploadForm;
use app\utils\GenerateShareQrcode;
use luweiss\wechat\Wechat;

/**
 * @property Wechat $wechat 小程序的
 */
class QrcodeForm extends ApiModel
{
//    public $order_no;
    public $data;
    public $scene;
    public $width;
    public $page;
    public $store;
    public $wechat;


    public function getQrcode()
    {
        $res = GenerateShareQrcode::getQrcode($this->store->id,$this->scene,$this->width,$this->page);
        if($res['code'] == 1){
            return $res;
        }
        //保存到本地
        $saveRoot = \Yii::$app->basePath . '/web/temp';
        $saveDir = '/';
        if (!is_dir($saveRoot . $saveDir)) {
            mkdir($saveRoot . $saveDir);
            file_put_contents($saveRoot . $saveDir . '.gitignore', "*\r\n!.gitignore");
        }
        $saveName = md5(uniqid()) . '.jpg';
        $webRoot = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $saveName);
        file_put_contents($saveRoot . $saveDir . $saveName, file_get_contents($res['file_path']));

        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'url'=>$webRoot
            ]
        ];
    }
}
