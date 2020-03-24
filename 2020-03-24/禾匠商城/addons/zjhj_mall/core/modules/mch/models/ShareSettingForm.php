<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 15:59
 */

namespace app\modules\mch\models;

use app\models\Setting;

/**
 * @property \app\models\Setting $list;
 */
class ShareSettingForm extends MchModel
{
    public $list;
    public $store_id;

    public $first;
    public $second;
    public $third;

    public $first_name;
    public $second_name;
    public $third_name;

    public $price_type;
    public $rebate;


    public function rules()
    {
        return [
            [['first', 'second', 'third', 'rebate'], 'number', 'min' => 0,'max'=>999999],
            [['first', 'second', 'third', 'rebate'], 'default', 'value' => 0],
            [['first_name', 'second_name', 'third_name'], 'trim'],
            [['first_name', 'second_name', 'third_name'], 'string', 'min' => 2, 'max' => 4],
            [['price_type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first' => '一级佣金比例',
            'second' => '二级佣金比例',
            'third' => '三级佣金比例',
            'first_name' => '一级名称',
            'second_name' => '二级名称',
            'third_name' => '三级名称',
            'price_type' => '佣金配比',
            'rebate' => '分销返利',
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $list = $this->list;
            $list->attributes = $this->attributes;
            if ($list->save()) {
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                return $this->getErrorResponse($list);
            }
        } else {
            return $this->errorResponse;
        }
    }
}
