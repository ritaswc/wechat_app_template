<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/22
 * Time: 14:53
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\utils\GenerateShareQrcode;
use Curl\Curl;

class GoodsQrcodeForm extends MchModel
{
    public $store_id;
    public $goods_id;
    public $user_id;
    public $plugin;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
        ];
    }

    public function search()
    {
        $goods = Goods::findOne($this->goods_id);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $model = new GenerateShareQrcode();
        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $goods_pic_save_name_wx = md5("v=2.7.8&goods_id={$goods->id}&goods_name={$goods->name}&store_id={$this->store_id}&wx") . '.jpg';
        $goods_pic_save_name_my = md5("v=2.7.8&goods_id={$goods->id}&goods_name={$goods->name}&store_id={$this->store_id}&alipay") . '.jpg';
        $goods_qrcode_path_wx = $goods_pic_save_path . $goods_pic_save_name_wx;
        $goods_qrcode_path_my = $goods_pic_save_path . $goods_pic_save_name_my;
        $page = "pages/index/index";
        if ($this->plugin == 0) {
            $page = 'pages/goods/goods';
        } else if ($this->plugin == 2) {
            $page = 'bargain/goods/goods';
        }
        $list = [];
        $wx = $model->wechat("gid:{$goods->id},uid:-1", 430, $page);
        if ($wx['code'] == 0) {
            $fp = fopen($goods_qrcode_path_wx, 'w');
            fwrite($fp, file_get_contents($wx['file_path']));
            fclose($fp);
            $goods_qrcode_url_wx = trim(strrchr($goods_qrcode_path_wx, '/'), '/');
            $pic_url_wx = str_replace('http://', 'http://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_qrcode_url_wx);
            $list['wx'] = $pic_url_wx . '?v=' . time();
        }
        $alipay = $model->alipay("gid={$goods->id}&uid=-1", $this->store_id, $page,'二维码');
        if ($alipay['code'] == 0) {
            $fp = fopen($goods_qrcode_path_my, 'w');
            fwrite($fp, file_get_contents($alipay['file_path']));
            fclose($fp);

            $goods_qrcode_url_my = trim(strrchr($goods_qrcode_path_my, '/'), '/');
            $pic_url_my = str_replace('http://', 'http://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_qrcode_url_my);
            $list['my'] = $pic_url_my . '?v=' . time();
        }
        return [
            'code' => 0,
            'data' => $list,
        ];
    }
}
