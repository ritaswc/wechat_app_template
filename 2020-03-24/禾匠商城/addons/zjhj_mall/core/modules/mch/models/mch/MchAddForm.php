<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/6/8
 * Time: 15:41
 */


namespace app\modules\mch\models\mch;

use app\models\Mch;
use app\models\User;
use app\modules\mch\models\MchModel;

class MchAddForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $realname;
    public $tel;
    public $name;
    public $province_id;
    public $city_id;
    public $district_id;
    public $address;
    public $mch_common_cat_id;
    public $service_tel;
    public $logo;
    public $header_bg;
    public $transfer_rate;
    public $sort;
    public $wechat_name;

    public function attributeLabels()
    {
        return [
            'user_id' => '小程序用户',
            'realname' => '联系人',
            'tel' => '联系电话',
            'name' => '店铺名称',
            'province_id' => '所在地区',
            'city_id' => '所在地区',
            'district_id' => '所在地区',
            'address' => '详细地址',
            'mch_common_cat_id' => '所售类目',
            'service_tel' => '客服电话',
            'logo' => '店铺Logo',
            'header_bg' => '店铺背景',
            'transfer_rate' => '手续费',
            'sort' => '排序',
            'wechat_name' => '微信号',
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'realname', 'tel', 'name', 'wechat_name',  'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel', 'logo', 'header_bg', 'transfer_rate', 'sort'], 'trim'],
            [['user_id', 'realname', 'tel', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel', 'transfer_rate',], 'required'],
            [['user_id', 'province_id', 'city_id', 'district_id', 'mch_common_cat_id', 'transfer_rate', 'sort'], 'integer'],
            ['sort', 'default', 'value' => 1000,],
            ['logo', 'default', 'value' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/shop-logo.png',],
            ['header_bg', 'default', 'value' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/shop-header-bg.jpg',],
            ['header_bg', 'default', 'value' => 1000,],
            ['transfer_rate', 'integer', 'min' => 0, 'max' => 1000,],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $exist_user = User::findOne([
            'id' => $this->user_id,
            'store_id' => $this->store_id,
        ]);
        if (!$exist_user) {
            return [
                'code' => 1,
                'msg' => '小程序用户不存在。',
            ];
        }
        $exist_mch = Mch::findOne([
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ]);
        if ($exist_mch) {
            return [
                'code' => 1,
                'msg' => '该小程序用户已经入驻。',
            ];
        }
        $mch = new Mch();
        $mch->attributes = $this->attributes;
        $mch->addtime = time();
        $mch->is_delete = 0;
        $mch->is_open = 1;
        $mch->is_lock = 0;
        $mch->review_status = 1;
        $mch->review_time = time();
        $mch->account_money = 0;
        if ($mch->save()) {
            return [
                'code' => 0,
                'msg' => '商户添加成功。',
            ];
        } else {
            return $this->getErrorResponse($mch);
        }
    }
}
