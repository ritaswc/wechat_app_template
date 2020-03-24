<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/4
 * Time: 10:34
 */

namespace app\modules\user\models\mch;


use app\models\DistrictArr;
use app\models\FreeDeliveryRules;
use app\models\MchOption;
use app\modules\user\models\UserModel;

class FreeDeliverForm extends UserModel
{
    public $store;
    public $mch;

    public $is_enable;
    public $total_price;
    public $rule_list;

    public function rules()
    {
        return [
            [['total_price'], 'default', 'value' => -1],
            [['total_price'], 'number', 'min' => -1, 'max' => 999999],
            [['is_enable'], 'integer'],
            [['rule_list'], 'safe']
        ];
    }

    public function search()
    {
        $model = MchOption::get('free-deliver-rules', $this->store->id, $this->mch->id, 'setting', null);

        if (!$model) {
            $isEnable = 0;
            $totalPrice = -1;
            $ruleList = [];
        } else {
            $isEnable = $model->is_enable;
            $totalPrice = $model->total_price;
            $ruleList = $model->rule_list ? $model->rule_list : [];
        }
        $provinceList = DistrictArr::getRules();

        return [
            'ruleList' => $ruleList,
            'province_list' => $provinceList,
            'is_enable' => $isEnable,
            'total_price' => $totalPrice
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = [
            'is_enable' => $this->is_enable,
            'total_price' => $this->total_price,
            'rule_list' => $this->rule_list
        ];

        MchOption::set('free-deliver-rules', $model, $this->store->id, $this->mch->id, 'setting');

        return [
            'code' => 0,
            'msg' => '保存成功'
        ];
    }
}