<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 16:57
 */

namespace app\models;

class RechargeModule extends Model
{
    public $store_id;

    public function search_recharge()
    {
        $list = [
            'status'=>0,
            'pic_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/recharge/icon-balance-bg.png',
            'ad_pic_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/recharge/icon-balance-ad.png',
            'page_url' => '/pages/recharge/recharge',
            'p_pic_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/recharge/icon-balance-p.png',
            'help' => '',
            'type' => 0,
        ];

        $data = Option::get('re_setting', $this->store_id, 'app');
        $new_list = [];
        if ($data) {
            $data = json_decode($data, true);
            foreach ($list as $index => $value) {
                if (isset($data[$index]) && $data[$index]) {
                    $new_list[$index] = $data[$index];
                } else {
                    $new_list[$index] = $value;
                }
            }
        } else {
            $new_list = $list;
        }
        return $new_list;
    }
}
