<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 17:05
 */

namespace app\modules\mch\models;

use app\models\Option;
use app\models\Store;

class ShareCustomForm extends MchModel
{
    public $store_id;
    public $data;
    public $store;

    public function rules()
    {
        return [
            [['data'], 'required'],
            [['data'], 'string'],
        ];
    }

    public function saveData()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        Option::set('share_custom_data', $this->data, $this->store_id);
        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }

    public function getData()
    {

        $data = Option::get('share_custom_data', $this->store_id);
        $default_data = $this->getDefaultData();
        if (!$data) {
            $data = $default_data;
        } else {
            $data = json_decode($data, true);
            $data = $this->checkData($data, $default_data);
        }
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    //检查是否有新增的值
    public function checkData($list = array(), $default_list = array())
    {
        $ignore = ['menu'];
        $new_list = [];
        foreach ($default_list as $index => $value) {
            if (isset($list[$index])) {
                if (is_array($value) && !in_array($index, $ignore)) {
                    $new_list[$index] = $this->checkData($list[$index], $value);
                } else {
                    $new_list[$index] = $list[$index];
                }
            } else {
                $new_list[$index] = $value;
            }
        }
        return $new_list;
    }


    public function getDefaultData()
    {
        return [
            'menus' => [
                'money'=>[
                    'name' => '分销佣金',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/share-custom/img-share-price.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-money/share-money',
                    'tel' => '',
                ],
                'order'=>[
                    'name' => '分销订单',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/share-custom/img-share-order.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-order/share-order',
                    'tel' => '',
                ],
                'cash'=>[
                    'name' => '提现明细',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/share-custom/img-share-cash.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/cash-detail/cash-detail',
                    'tel' => '',
                ],
                'team'=>[
                    'name' => '我的团队',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/share-custom/img-share-team.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-team/share-team',
                    'tel' => '',
                ],
                'qrcode'=>[
                    'name' => '推广二维码',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/share-custom/img-share-qrcode.png',
                    'open_type' => 'navigator',
                    'url' => '/pages/share-qrcode/share-qrcode',
                    'tel' => '',
                ],
            ],
            'words' => [
                'can_be_presented'=>[
                    'name' => '可提现佣金',
                    'default' => '可提现佣金',
                ],
                'already_presented'=>[
                    'name' => '已提现佣金',
                    'default' => '已提现佣金',
                ],
                'parent_name'=>[
                    'name' => '推荐人',
                    'default' => '推荐人',
                ],
                'pending_money'=>[
                    'name' => '待打款佣金',
                    'default' => '待打款佣金',
                ],
                'cash'=>[
                    'name' => '提现',
                    'default' => '提现',
                ],
                'user_instructions'=>[
                    'name' => '用户须知',
                    'default' => '用户须知',
                ],
                'apply_cash'=>[
                    'name' => '我要提现',
                    'default' => '我要提现',
                ],
                'cash_type'=>[
                    'name' => '提现方式',
                    'default' => '提现方式',
                ],
                'cash_money'=>[
                    'name' => '提现金额',
                    'default' => '提现金额',
                ],
                'order_money_un'=>[
                    'name' => '未结算佣金',
                    'default' => '未结算佣金',
                ],
                'share_name'=>[
                    'name' => '分销商',
                    'default' => '分销商',
                ],
            ]
        ];
    }
}
