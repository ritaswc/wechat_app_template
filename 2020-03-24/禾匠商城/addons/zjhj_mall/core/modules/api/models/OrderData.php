<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 10:22
 */

namespace app\modules\api\models;

use app\models\Address;
use app\models\Option;

/**
 * @property \app\models\Address $address
 */
class OrderData extends ApiModel
{
    public $store_id;
    public $user_id;
    public $address;
    public $offline;
    public $total_price;
    public $address_id;

    public $cart_id_list;
    public $goods_info;
    public $mch_list;

    /**
     * 支付方式
     * @param $store_id
     * @param array $is_payment //支付方式
     * @param array $ignore //忽略的支付方式
     * @return array
     */
    public static function getPayType($store_id, $is_payment = array(), $ignore = array())
    {
        if (!$is_payment || empty($is_payment)) {
            $pay_str = Option::get('payment', $store_id, 'admin', '{"wechat":"1"}');
            $is_payment = json_decode($pay_str, true);
        }
        $pay_type_list = [];
        foreach ($is_payment as $index => $value) {
            if (in_array($index, $ignore)) {
                continue;
            }
            if ($index == 'wechat' && $value == 1) {
                $pay_type_list[] = [
                    'name' => '线上支付',
                    'payment' => 0,
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/wxapp/images/icon-payment-online.png'
                ];
            }
            if ($index == 'huodao' && $value == 1) {
                $pay_type_list[] = [
                    'name' => '货到付款',
                    'payment' => 2,
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/wxapp/images/icon-payment-huodao.png'
                ];
            }
            if ($index == 'balance' && $value == 1) {
                $balance = Option::get('re_setting', $store_id, 'app');
                $balance = json_decode($balance, true);
                if ($balance && $balance['status'] == 1) {
                    $pay_type_list[] = [
                        'name' => '账户余额支付',
                        'payment' => 3,
                        'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/wxapp/images/icon-payment-balance.png'
                    ];
                }
            }
        }
        if (!$pay_type_list) {
            $pay_type_list[] = [
                'name' => '线上支付',
                'payment' => 0,
                'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/recharge/icon-online.png'
            ];
        }
        return $pay_type_list;
    }



    //积分计算
    /**
     * @param $goods_item object 重新编写的goods_item
     * @param $store_integral int 商城设置的积分规则
     * @param $goods_id array 已设置积分的商品id数组
     * @return array
     */
    public static function integral($goods_item, $store_integral, $goods_id = array())
    {
        $integral = json_decode($goods_item->integral, true);
        $resIntegral = [
            'forehead' => 0,
            'forehead_integral' => 0,
        ];
        if ($integral) {
            //赠送积分计算
            $give = $integral['give'];
            if (strpos($give, '%') !== false) {
                // 百分比
                $give = trim($give, '%');
                $goods_item->give = (int)($goods_item->price * ($give / 100));
            } else {
                // 固定积分
                $goods_item->give = (int)($give * $goods_item->num);
            }
            //抵扣积分计算
            $forehead = $integral['forehead'] ? $integral['forehead'] : 0;
            if (strpos($forehead, '%') !== false) {//百分比积分抵扣计算
//                $forehead = (int)trim($forehead, '%');
                if ($forehead >= 100) {
                    $forehead = 100;
                }
                if ($integral['more'] == '1') {//多件累计计算
                    $resIntegral['forehead_integral'] = (int)(($forehead / 100) * $goods_item->price * $store_integral);
                } else {
                    if (!in_array($goods_item->id, $goods_id)) { //不允许多件累计   同id商品值计算一次积分抵扣
                        $resIntegral['forehead_integral'] = (int)(($forehead / 100) * $goods_item->single_price * $store_integral);
                    }
                }
            } else {
//                $forehead = (int)$forehead;
                if ($integral['more'] == '1') {
                    $resIntegral['forehead_integral'] = (int)($store_integral * $goods_item->price);
                    if ($goods_item->price > ($forehead * $goods_item->num)) {
                        $resIntegral['forehead_integral'] = (int)($forehead * $goods_item->num * $store_integral);
                    }
                } else {
                    if (!in_array($goods_item->id, $goods_id)) {
                        $goodsPrice = $goods_item->single_price;
                        $resIntegral['forehead_integral'] = (int)($store_integral * $goodsPrice);
                        if ($goodsPrice > $forehead) {
                            $resIntegral['forehead_integral'] = (int)($forehead * $store_integral);
                        }
                    }
                }
            }
            $resIntegral['forehead_integral'] = $resIntegral['forehead_integral'] >= \Yii::$app->user->identity->integral ? \Yii::$app->user->identity->integral : $resIntegral['forehead_integral'];
            $resIntegral['forehead'] = sprintf("%.2f", ($resIntegral['forehead_integral'] / $store_integral));
        }


        return [
            'resIntegral' => $resIntegral,
            'give' => $goods_item->give
        ];
    }

    // 起送规则计算
    public function checkOfferRule()
    {
        if ($this->offline != 0) {
            return [
                'code' => 0,
                'msg' => '不是快递配送'
            ];
        }
        if (!$this->address) {
            return [
                'code' => 1,
                'msg' => '请选择收货地址'
            ];
        }
        $offerRule = Option::get('offer-price', $this->store_id, 'admin');
        if (!$offerRule) {
            return [
                'code' => 0,
                'msg' => '起送规则不存在'
            ];
        }
        if ($offerRule->is_enable == 0) {
            return [
                'code' => 0,
                'msg' => '起送规则未开启'
            ];
        }
        $ruleList = $offerRule->rule_list;
        if (is_array($ruleList)) {
            foreach ($ruleList as $value) {
                foreach ($value['province_list'] as $item) {
                    if ($item['id'] == $this->address->city_id) {
                        if ($value['price'] <= $this->total_price) {
                            return [
                                'code' => 0,
                                'msg' => 'success'
                            ];
                        } else {
                            return [
                                'code' => 1,
                                'msg' => "自营商品，当前地区满{$value['price']}元起送"
                            ];
                        }
                    }
                }
            }
        }
        if ($offerRule->total_price <= $this->total_price) {
            return [
                'code' => 0,
                'msg' => 'success'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => "自营商品，当前地区满{$offerRule->total_price}元起送"
            ];
        }
    }

    // 获取起送规则
    public function getOfferRule()
    {
        $res = [
            'is_allowed' => 0,
            'total_price' => 0,
            'msg' => ''
        ];
        $cartIdList = json_decode($this->cart_id_list);
        if ($this->cart_id_list && empty($cartIdList)) {
            $res['msg'] = "不是商城订单";
            return $res;
        }
        if ($this->goods_info && $this->mch_list) {
            $res['msg'] = "不是商城订单";
            return $res;
        }
        if (!$this->address) {
            $res['msg'] = '请选择收货地址';
            return $res;
        }
        $offerRule = Option::get('offer-price', $this->store_id, 'admin');
        if (!$offerRule) {
            $res['msg'] = '起送规则不存在';
            return $res;
        }
        if ($offerRule->is_enable == 0) {
            $res['msg'] = '起送规则未开启';
            return $res;
        }

        $ruleList = $offerRule->rule_list;

        $res['total_price'] = $offerRule->total_price;
        if (is_array($ruleList)) {
            foreach ($ruleList as $value) {
                foreach ($value['province_list'] as $item) {
                    if ($item['id'] == $this->address['city_id']) {
                        $res['total_price'] = $value['price'];
                    }
                }
            }
        }

        if ($this->total_price >= $res['total_price']) {
            $res['is_allowed'] = 0;
        } else {
            $res['is_allowed'] = 1;
        }
        $value = round($res['total_price'] - $this->total_price, 2);
        $res['msg'] = "还差{$value}元起送";

        return $res;
    }

    // 获取用户地址 OrderSubmitPreview
    public function getAddress()
    {
        $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')->where([
            'id' => $this->address_id,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ])->asArray()->one();
        if (!$address) {
            $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
                'is_delete' => 0,
            ])->orderBy('is_default DESC,addtime DESC')->asArray()->one();
        }
        return $address;
    }
}
