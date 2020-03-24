<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:48
 */

namespace app\modules\api\models;

use app\models\Address;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\Cart;
use app\models\Cat;
use app\models\Coupon;
use app\models\CouponAutoSend;
use app\models\Form;
use app\models\FreeDeliveryRules;
use app\models\Goods;
use app\models\GoodsCat;
use app\models\Level;
use app\models\Mch;
use app\models\MiaoshaGoods;
use app\models\Option;
use app\models\PostageRules;
use app\models\Shop;
use app\models\Store;
use app\models\TerritorialLimitation;
use app\models\User;
use app\models\UserCoupon;

class OrderSubmitPreviewForm extends OrderData
{
    public $store;
    public $store_id;
    public $user_id;
    public $step_id;

    public $address_id;
    public $cart_list;

    public $cart_id_list;
    public $goods_info;

    public $longitude;
    public $latitude;

    public $type; //订单类型 s--商城订单  ms--秒杀订单
    public $mch_list;

    public function rules()
    {
        return [
            [['cart_id_list', 'goods_info', 'cart_list', 'mch_list'], 'string'],
            [['address_id', 'step_id'], 'integer'],
            [['longitude', 'latitude'], 'trim'],
        ];
    }

    /**
     * @return array
     * [
     * 'address'=>{},
     * 'cart_id_list'=>[] 或 'goods_info'=>{} 或 'cart_list'=>[],
     * 'coupon_list'=>[],
     * 'express_price'=>"",
     * 'form_list'=>{},
     * 'goods_card_list'=>[],
     * 'integral'=>{},
     * 'is_payment'=>{},
     * 'is_shop'=>'',
     * 'shop_list'=>[],
     * 'level'=>'',
     * 'list'=>[],
     * 'pay_type_list'=>[],
     * 'send_type'=>'',
     * total_price'=>''
     * ]
     */
    public function search()
    {
        $store = Store::findOne($this->store_id);
        $this->store = $store;
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        //        快速购买
        if ($this->cart_list) {
            $res = $this->getCartList($this->cart_list, $store);
        }
        //购物车（新增多商户的商品）
        if ($this->cart_id_list || $this->mch_list) {
            $res = $this->getDataByCartIdList($this->cart_id_list, $store);
            if ($this->mch_list) {
                $res['data']['mch_list'] = [];
                $mch_list = json_decode($this->mch_list, true);
                if (is_array($mch_list)) {
                    foreach ($mch_list as $mch) {
                        $mch_res = $this->getDataByCartIdList(json_encode($mch['cart_id_list'], JSON_UNESCAPED_UNICODE), $store);
                        $mch = Mch::findOne($mch['id']);
                        if ($mch_res['code'] == 0 && $mch) {
                            $mch_res['data']['id'] = $mch->id;
                            $mch_res['data']['name'] = $mch->name;
                            $res['data']['mch_list'][] = $mch_res['data'];
                        }
                    }
                }
                if (count($res['data']['mch_list'])) {
                    $res['code'] = 0;
                }
            }
        }

        //商品直接购买
        if ($this->goods_info) {
            $res = $this->getDataByGoodsInfo($this->goods_info, $store);
        }

        if ($this->type == 'ms') {
            $buyMaxRes = $this->checkBuyMax($res['data']['list']);
            if ($buyMaxRes) {
                return $buyMaxRes;
            }
        }
        if ($res['code'] != 0) {
            return $res;
        }
        $this->address = $res['data']['address'];
        // 获取起送规则
        $this->total_price = $res['data']['total_price']; // 暂时只计算商城价格（不包含多商户）
        $this->mch_list = $res['data']['mch_list'];
        $res['data']['offer_rule'] = $this->getOfferRule();

        $goods_id = [];
        $goods_cat_id = [];
        $coupon_goods_id = [];
        foreach ($res['data']['list'] as $key => $value) {
            if ($res['data']['list'][$key]->cat_id == 0) {
                // 多分类商品id
                $goods_id[] = $res['data']['list'][$key]->goods_id;
            } else {
                // 单分类商品cat_id
                $goods_cat_id[] = $res['data']['list'][$key]->cat_id;
            }
            $coupon_goods_id[] = $res['data']['list'][$key]->goods_id;
        }
        $goods_cat_id = array_unique($goods_cat_id); //单分类商品cat_id
        $many_cat_goods = GoodsCat::find()->select('cat_id')->where(['is_delete' => 0, 'goods_id' => $goods_id])->asArray()->all();
        $many_cat_id = [];
        foreach ($many_cat_goods as $key => $value) {
            $many_cat_id[] = $value['cat_id']; // 多分类商品cat_id
        }
        $cat_id = array_merge($goods_cat_id, $many_cat_id);

        $cat = Cat::find()->where(['is_delete' => 0, 'store_id' => $store->id, 'id' => $cat_id])->asArray()->all();

        $goods_cat_ids = [];
        $many_cat_ids = [];
        foreach ($cat as $key2 => $value2) {
            $goods_cat_ids[] = $value2['id'];
            if ($value2['parent_id'] != 0) {
                $many_cat_ids[] = $value2['parent_id'];
            }
        }
        $cat_ids = array_merge($goods_cat_ids, $many_cat_ids);

        $res['data']['coupon_list'] = $this->getCouponList($res['data']['total_price'], array_unique($cat_ids), $coupon_goods_id);

        if ($store->send_type != 1) {
            $res['data']['shop_list'] = $this->getShopList();
            $res['data']['is_shop'] = Shop::find()->where(['is_default' => 1, 'store_id' => $this->store_id])->asArray()->one();
        } else {
            $res['data']['shop_list'] = [];
            $res['data']['is_shop'] = '';
        }
//        }
        $level = Level::find()->select([
            'name', 'level', 'discount',
        ])->where(['level' => \Yii::$app->user->identity->level, 'store_id' => $this->store_id])->asArray()->one();
        $res['data']['level'] = $level;
        //获取商城发货方式
        $res['data']['send_type'] = $store->send_type;
        // 获取 店铺积分使用规则
        if (!empty($res['data']['integral'])) {
            if (is_array($res['data']['integral'])) {
                $res['data']['integral']['integration'] = $store->integration;
            }

            if (is_object($res['data']['integral']) && isset($res['data']['integral']->integration)) {
                $res['data']['integral']->integration = $store->integration;
            }
        }
        // 获取用户当前积分
        $user = User::findOne(['id' => $this->user_id, 'type' => 1, 'is_delete' => 0]);
        if ($user->integral < $res['data']['integral']['forehead_integral']) {
            $res['data']['integral']['forehead_integral'] = $user->integral;
            $res['data']['integral']['forehead'] = sprintf("%.2f", $user->integral / $store->integral);
        }
        $res['data']['form'] = $this->formData();

        //包邮规则
        if ($res['data']['express_price'] != 0) {
            $free = FreeDeliveryRules::find()->where(['store_id' => $this->store_id])->asArray()->all();
            foreach ($free as $k => $v) {
                $city = json_decode($v['city'], true);
                foreach ($city as $v1) {
                    if ($res['data']['address']['city_id'] == $v1['id'] && $res['data']['total_price'] >= $v['price'] && !$this->step_id) {
                        $res['data']['express_price'] = 0;
                        break;
                    }
                }
            }
        }
        //支付方式
        $pay_str = Option::get('payment', $store->id, 'admin', '{"wechat":"1"}');
        $is_payment = json_decode($pay_str, true);
        $res['data']['is_payment'] = $is_payment;
        $pay_type_list = OrderData::getPayType($this->store_id, $is_payment);
        $res['data']['pay_type_list'] = $pay_type_list;

        $res['data']['is_area'] = 0;
        $area = TerritorialLimitation::findOne([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'is_enable' => 1,
        ]);
        if ($area) {
            if (!$res['data']['address']) {
                $res['data']['is_area'] = 0;
                $res['data']['is_area_city_id'] = [];
            }
            $city_id = []; //限制的地区ID
            $detail = json_decode($area->detail);
            if (!is_array($detail)) {
                $detail = [];
            }

            foreach ($detail as $key => $value) {
                foreach ($value->province_list as $key2 => $value2) {
                    $city_id[] = $value2->id;
                }
            }
            $addressArr = [
                $res['data']['address']['province_id'],
                $res['data']['address']['city_id'],
                $res['data']['address']['district_id']
            ];
            $addressArray = array_intersect($addressArr, $city_id);
            if (empty($addressArray)) {
                $res['data']['is_area'] = 1;
            }
            $res['data']['is_area_city_id'] = $city_id;
        }

        // 处理PHP浮点数计算产生的小数
        if (!empty($res['data']['list']) && is_array($res['data']['list'])) {
            foreach ($res['data']['list'] as &$item) {
                if (is_object($item) && !empty($item->price)) {
                    $item->price = sprintf('%.2f', $item->price);
                }
            }
            unset($item);
        }
        if (!empty($res['data']['integral']['forehead_integral'])) {
            $res['data']['integral']['forehead_integral'] = sprintf('%.2f', $res['data']['integral']['forehead_integral']);
        }
        if (!empty($res['data']['mch_list']) && is_array($res['data']['mch_list'])) {
            foreach ($res['data']['mch_list'] as &$mch) {
                if (!empty($mch['list']) && is_array($mch['list'])) {
                    foreach ($mch['list'] as &$item) {
                        if (is_object($item) && !empty($item->price)) {
                            $item->price = sprintf('%.2f', $item->price);
                        }
                    }
                    unset($item);
                }
            }
            unset($mch);
        }

        //处理只有多商户商品下单提示必须选择门店的问题
        if (is_array($res['data']['list']) && count($res['data']['list']) == 0 && is_array($res['data']['mch_list']) && count($res['data']['mch_list']) > 0) {
            $res['data']['send_type'] = 0;
        }

        return $res;
    }

    private function getCouponList($goods_total_price, $cat_ids, $coupon_goods_id)
    {
        $list = UserCoupon::find()->alias('uc')
            ->leftJoin(['c' => Coupon::tableName()], 'uc.coupon_id=c.id')
            ->leftJoin(['cas' => CouponAutoSend::tableName()], 'uc.coupon_auto_send_id=cas.id')
            ->where([
                'AND',
                ['uc.is_delete' => 0],
                ['uc.is_use' => 0],
                ['uc.is_expire' => 0],
                ['uc.user_id' => $this->user_id],
                ['<=', 'c.min_price', $goods_total_price],
            ])
            ->select('uc.id user_coupon_id,c.sub_price,c.min_price,cas.event,uc.begin_time,uc.end_time,uc.type,c.appoint_type,c.cat_id_list,c.goods_id_list')
            ->asArray()->all();
        $events = [
            0 => '平台发放',
            1 => '分享红包',
            2 => '购物返券',
            3 => '领券中心',
        ];
        $new_list = [];
        foreach ($list as $i => $item) {
            if ($item['begin_time'] > (strtotime(date('Y-M-d')) + 86400) || $item['end_time'] < time()) {
                continue;
            }
            $list[$i]['status'] = 0;
            if ($item['is_use']) {
                $list[$i]['status'] = 1;
            }

            if ($item['is_expire']) {
                $list[$i]['status'] = 2;
            }

            $list[$i]['min_price_desc'] = $item['min_price'] == 0 ? '无门槛' : '满' . $item['min_price'] . '元可用';
            $list[$i]['begin_time'] = date('Y.m.d H:i', $item['begin_time']);
            $list[$i]['end_time'] = date('Y.m.d H:i', $item['end_time']);
            if (!$item['event']) {
                if ($item['type'] == 2) {
                    $list[$i]['event'] = $item['event'] = 3;
                } else {
                    $list[$i]['event'] = $item['event'] = 0;
                }
            }
            $list[$i]['event_desc'] = $events[$item['event']];
            $list[$i]['min_price'] = doubleval($item['min_price']);
            $list[$i]['sub_price'] = doubleval($item['sub_price']);

            if ($list[$i]['appoint_type'] == 1) {
                $list[$i]['cat_id_list'] = json_decode($list[$i]['cat_id_list']);
                if ($list[$i]['cat_id_list'] != null) {
                    if (!array_intersect($list[$i]['cat_id_list'], $cat_ids)) {
                        unset($list[$i]);
                        continue;
                    }
                }
            } elseif ($list[$i]['appoint_type'] == 2) {
                $list[$i]['goods_id_list'] = json_decode($list[$i]['goods_id_list']);
                if ($list[$i]['goods_id_list'] != null) {
                    if (!array_intersect($list[$i]['goods_id_list'], $coupon_goods_id)) {
                        unset($list[$i]);
                        continue;
                    }
                }
            }

            $new_list[] = $list[$i];
        }
        return $new_list;
    }

    /**
     * @param $cart_list json eg:[{"id":"39","num":2,"attr":""},{"id":"140","num":2,"attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":1,"attr_name":"白色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":4,"attr_name":"M"},{"attr_group_id":22,"attr_group_name":"机箱","attr_id":293,"attr_name":"外机"}]}]
     * @param $store object
     * @return array
     */
    private function getCartList($cart_list, $store)
    {
        $cart_list = json_decode($cart_list);
        $list = [];
        $total_price = 0;
        $new_cart_id_list = [];
        $goodsList = [];
        $resIntegral = [
            'forehead' => 0,
            'forehead_integral' => 0,
        ];
        $goodsIds = [];
        $goods_card_list = [];
        foreach ($cart_list as $item) {
            $goods = Goods::findOne([
                'store_id' => $this->store_id,
                'id' => $item->id,
                'is_delete' => 0,
                'status' => 1,
            ]);
            if (!$goods) {
                continue;
            }

            if (!$goods->use_attr) {
                $gf = new GoodsForm();
                $gf->id = $goods->id;
                $gf->store_id = $goods->store_id;
                $gfr = $gf->search();
                if ($gfr->code == 0) {
                    $attr_group_list = $gfr->data['attr_group_list'];
                    $item->attr = [];
                    foreach ($attr_group_list as $attr_group) {
                        $item->attr[] = (object)[
                            'attr_group_id' => $attr_group->attr_group_id,
                            'attr_group_name' => $attr_group->attr_group_name,
                            'attr_id' => $attr_group->attr_list[0]->attr_id,
                            'attr_name' => $attr_group->attr_list[0]->attr_name,
                        ];
                    }
                }
            }
            $attr_id_list = [];
            foreach ($item->attr as $key => $value) {
                array_push($attr_id_list, $value->attr_id);
            }
            $goods_attr_info = $goods->getAttrInfo($attr_id_list);
            $attr_num = intval(empty($goods_attr_info['num']) ? 0 : $goods_attr_info['num']);
            $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
            if ($attr_num < $item->num) {
                continue;
            }

            $new_item = (object)[
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'goods_pic' => $goods_pic,
                'num' => $item->num,
                'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $item->num,
                'attr_list' => $item->attr,
                'give' => 0,
                'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
                'freight' => $goods->freight,
                'integral' => $goods->integral,
                'weight' => $goods->weight,
                'full_cut' => $goods->full_cut,
                'cat_id' => $goods->cat_id,
            ];

            $total_price += $new_item->price;
            $new_cart_id_list[] = $item->id;
            $list[] = $new_item;
            $goods_card = Goods::getGoodsCard($goods->id);
            $goods_card_list = array_merge($goods_card_list, $goods_card);
            $new_goods = [
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'freight' => $goods->freight,
                'weight' => $goods->weight,
                'num' => $item->num,
                'full_cut' => $goods->full_cut,
                'price' => $new_item->price,
            ];


            $goodsList[] = $new_goods;
            // 单个商品积分计算
            $arr = OrderData::integral($new_item, $store->integral, $goodsIds);
            $resIntegral['forehead_integral'] += $arr['resIntegral']['forehead_integral'];
            $resIntegral['forehead'] += $arr['resIntegral']['forehead'];
            $goodsIds[] = $goods->id;
        }
        $address = $this->getAddress();
        $express_price = 0;
        if (count($list)) {
            if ($address) {
                $resGoodsList = Goods::cutFull($goodsList);
                $express_price = PostageRules::getExpressPriceMore($this->store_id, $address['city_id'], $resGoodsList, $address['province_id']);
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'total_price' => $total_price,
                'list' => $list,
                'cart_list' => $cart_list,
                'address' => $address,
                'express_price' => $express_price,
                'integral' => $resIntegral,
                'goods_card_list' => $goods_card_list,
            ],
        ];
    }

    /**
     * @param string $cart_id_list eg. [12,32,7]
     */
    private function getDataByCartIdList($cart_id_list, $store)
    {
        /* @var  Cart[] $cart_list */
        $cart_list = Cart::find()->where([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
            'id' => json_decode($cart_id_list, true),
        ])->all();

        $list = [];
        $total_price = 0;
        $new_cart_id_list = [];
        $goodsList = [];
        $resIntegral = [
            'forehead' => 0,
            'forehead_integral' => 0,
        ];
        $goodsIds = [];
        $goods_card_list = [];
        foreach ($cart_list as $item) {
            $goods = Goods::findOne([
                'store_id' => $this->store_id,
                'id' => $item->goods_id,
                'is_delete' => 0,
                'status' => 1,
            ]);
            if (!$goods) {
                continue;
            }

            $attr_list = Attr::find()->alias('a')
                ->select('ag.attr_group_name,a.attr_name')
                ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
                ->where(['a.id' => json_decode($item->attr, true)])
                ->asArray()->all();
            $goods_attr_info = $goods->getAttrInfo(json_decode($item->attr, true));
            $attr_num = intval(empty($goods_attr_info['num']) ? 0 : $goods_attr_info['num']);
            $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
            if ($attr_num < $item->num) {
                continue;
            }

            $goods_item = (object)[
                'cart_id' => $item->id,
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'goods_pic' => $goods_pic,
                'num' => $item->num,
                'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $item->num,
                'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
                'attr_list' => $attr_list,
                'give' => 0,
                'freight' => $goods->freight,
                'integral' => $goods->integral,
                'weight' => $goods->weight,
                'full_cut' => $goods->full_cut,
                'cat_id' => $goods->cat_id,
                'mch_id' => $goods->mch_id,
            ];

            $total_price += $goods_item->price;
            $new_cart_id_list[] = $item->id;
            $list[] = $goods_item;
            $goods_card = Goods::getGoodsCard($goods->id);
            $goods_card_list = array_merge($goods_card_list, $goods_card);
            $new_goods = [
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'freight' => $goods->freight,
                'weight' => $goods->weight,
                'num' => $item->num,
                'full_cut' => $goods->full_cut,
                'price' => $goods_item->price,
                'mch_id' => $goods->mch_id,
            ];

            $goodsList[] = $new_goods;

            // 单个商品积分计算
            $arr = OrderData::integral($goods_item, $store->integral, $goodsIds);
            $resIntegral['forehead_integral'] += $arr['resIntegral']['forehead_integral'];
            $resIntegral['forehead'] += $arr['resIntegral']['forehead'];

            $goodsIds[] = $goods->id;
        }
        //地区
        $address = $this->getAddress();
        $express_price = 0;
        if (count($list)) {
            //多件商品运费
            if ($address) {
                //先计算单品满件包邮和满额包邮
                $resGoodsList = Goods::cutFull($goodsList);
                //再通过运费规则计算运费
                $express_price = PostageRules::getExpressPriceMore($this->store_id, $address['city_id'], $resGoodsList, $address['province_id']);
            }
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'total_price' => $total_price,
                'cart_id_list' => $new_cart_id_list,
                'list' => $list,
                'address' => $address,
                'express_price' => round($express_price, 2),
                'integral' => $resIntegral,
                'goods_card_list' => $goods_card_list,
            ],
        ];
    }

    /**
     * @param string $goods_info
     * JSON,eg.{"goods_id":"22","attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":3,"attr_name":"橙色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":7,"attr_name":"L"}],"num":1}
     */
    private function getDataByGoodsInfo($goods_info, $store)
    {
        $goods_info = json_decode($goods_info);
        $goods = Goods::findOne([
            'id' => $goods_info->goods_id,
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'status' => 1,
        ]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        }

        $attr_id_list = [];
        foreach ($goods_info->attr as $item) {
            array_push($attr_id_list, $item->attr_id);
        }
        $total_price = 0;
        $goods_attr_info = $goods->getAttrInfo($attr_id_list);

        $attr_list = Attr::find()->alias('a')
            ->select('ag.attr_group_name,a.attr_name')
            ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
            ->where(['a.id' => $attr_id_list])
            ->asArray()->all();
        $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
        $goods_item = (object)[
            'goods_id' => $goods->id,
            'goods_name' => $goods->name,
            'goods_pic' => $goods_pic,
            'num' => $goods_info->num,
            'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $goods_info->num,
            'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
            'attr_list' => $attr_list,
            'give' => 0,
            'freight' => $goods->freight,
            'integral' => $goods->integral,
            'weight' => $goods->weight,
            'full_cut' => $goods->full_cut,
            'cat_id' => $goods->cat_id,
            'mch_id' => $goods->mch_id,
        ];

        $list = [];
        $mch_list = [];
        //地址
        $address = $this->getAddress();
        $express_price = 0;
        $resIntegral = 0;
        $goods_card_list = [];

        if ($goods->mch_id) { //入驻商的商品
            $mch = Mch::findOne($goods->mch_id);
            //运费
            $express_price = $this->Express($goods_item, $address, $goods->full_cut);
            $mch_list[] = [
                'id' => $mch->id,
                'name' => $mch->name,
                'logo' => $mch->logo,
                'express_price' => $express_price,
                'total_price' => $goods_item->price,
                'list' => [$goods_item],
            ];
        } else { //自营的商品
            $total_price = $goods_item->price;
            //运费
            $express_price = $this->Express($goods_item, $address, $goods->full_cut);
            //积分计算
            $integral_res = OrderData::integral($goods_item, $store->integral);
            $resIntegral = $integral_res['resIntegral'];
            $goods_item->give = $integral_res['give'];
            $goods_card_list = Goods::getGoodsCard($goods->id);
            $list[] = $goods_item;
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'total_price' => $total_price,
                'goods_info' => $goods_info,
                'list' => $list,
                'address' => $address,
                'express_price' => $express_price,
                'integral' => $resIntegral,
                'goods_card_list' => $goods_card_list,
                'mch_list' => $mch_list,
            ],
        ];
    }

    private function getShopList()
    {
        $list = Shop::find()->select(['address', 'mobile', 'id', 'name', 'longitude', 'latitude'])
            ->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        $distance = array();
        foreach ($list as $index => $item) {
            $list[$index]['distance'] = -1;
            if ($item['longitude'] && $this->longitude) {
                $from = [$this->longitude, $this->latitude];
                $to = [$item['longitude'], $item['latitude']];
                $list[$index]['distance'] = $this->get_distance($from, $to, false, 2);
            }
            $distance[] = $list[$index]['distance'];
        }
        array_multisort($distance, SORT_ASC, $list);
        $min = min(count($list), 30);
        $list_arr = array();
        foreach ($list as $index => $item) {
            if ($index <= $min) {
                $list[$index]['distance'] = $this->distance($item['distance']);
                array_push($list_arr, $list[$index]);
            }
        }
        return $list;
    }

    /**
     * @param Goods $goods
     * @param array $attr_id_list eg.[12,34,22]
     * @return array ['attr_list'=>[],'miaosha_price'=>'秒杀价格','miaosha_num'=>'秒杀数量','sell_num'=>'已秒杀商品数量']
     */
    private function getMiaoshaData($goods, $attr_id_list = [])
    {
        $miaosha_goods = MiaoshaGoods::findOne([
            'goods_id' => $goods->id,
            'is_delete' => 0,
            'open_date' => date('Y-m-d'),
            'start_time' => intval(date('H')),
        ]);
        if (!$miaosha_goods) {
            return null;
        }

        $attr_data = json_decode($miaosha_goods->attr, true);
        sort($attr_id_list);
        $miaosha_data = null;
        foreach ($attr_data as $i => $attr_data_item) {
            $_tmp_attr_id_list = [];
            foreach ($attr_data_item['attr_list'] as $item) {
                $_tmp_attr_id_list[] = $item['attr_id'];
            }
            sort($_tmp_attr_id_list);
            if ($attr_id_list == $_tmp_attr_id_list) {
                $miaosha_data = $attr_data_item;
                break;
            }
        }
        return $miaosha_data;
    }

    /**
     * 获取商品秒杀价格，若库存不足则使用商品原价，若有部分库存，则部分数量使用秒杀价，部分使用商品原价，商品库存不足返回false
     * @param array $miaosha_data ['attr_list'=>[],'miaosha_price'=>'秒杀价格','miaosha_num'=>'秒杀数量','sell_num'=>'已秒杀商品数量']
     * @param Goods $goods
     * @param array $attr_id_list eg.[12,34,22]
     * @param integer $buy_num 购买数量
     *
     * @return false|float
     */
    private function getMiaoshaPrice($miaosha_data, $goods, $attr_id_list, $buy_num)
    {
        $attr_data = json_decode($goods->attr, true);
        sort($attr_id_list);
        $goost_attr_data = null;
        foreach ($attr_data as $i => $attr_data_item) {
            $_tmp_attr_id_list = [];
            foreach ($attr_data_item['attr_list'] as $item) {
                $_tmp_attr_id_list[] = intval($item['attr_id']);
            }
            sort($_tmp_attr_id_list);
            if ($attr_id_list == $_tmp_attr_id_list) {
                $goost_attr_data = $attr_data_item;
                break;
            }
        }
        $goods_price = $goost_attr_data['price'];
        if (!$goods_price) {
            $goods_price = $goods->price;
        }

        $miaosha_price = min($miaosha_data['miaosha_price'], $goods_price);

        if ($buy_num > $goost_attr_data['num']) { //商品库存不足
            \Yii::warning([
                'res' => '库存不足',
                'm_data' => $miaosha_data,
                'g_data' => $goost_attr_data,
                '$attr_id_list' => $attr_id_list,
            ]);
            return false;
        }

        if ($buy_num <= ($miaosha_data['miaosha_num'] - $miaosha_data['sell_num'])) {
            \Yii::warning([
                'res' => '库存充足',
                'price' => $buy_num * $miaosha_price,
                'm_data' => $miaosha_data,
            ]);
            return $buy_num * $miaosha_price;
        }

        $miaosha_num = ($miaosha_data['miaosha_num'] - $miaosha_data['sell_num']);
        $original_num = $buy_num - $miaosha_num;

        \Yii::warning([
            'res' => '部分充足',
            'price' => $miaosha_num * $miaosha_price + $original_num * $goods_price,
            'm_data' => $miaosha_data,
        ]);
        return $miaosha_num * $miaosha_price + $original_num * $goods_price;
    }

    private static function distance($distance)
    {
        if ($distance == -1) {
            return -1;
        }
        if ($distance > 1000) {
            $distance = round($distance / 1000, 2) . 'km';
        } else {
            $distance .= 'm';
        }
        return $distance;
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from  [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    public function get_distance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }

    /**
     * 检查订单中是否有秒杀商品并且限购
     * @return null||array null表示无限购
     */
    public function checkBuyMax($list)
    {
        $goods_list = [];
        foreach ($list as $item) {
            if (empty($goods_list[$item->goods_id])) {
                $goods_list[$item->goods_id] = [
                    'goods_name' => $item->goods_name,
                    'num' => $item->num,
                ];
            } else {
                $goods_list[$item->goods_id]['num'] += intval($item->num);
            }
        }

        foreach ($goods_list as $goods_id => $item) {
            $miaosha_goods = MiaoshaGoods::find()->where([
                'AND',
                [
                    'goods_id' => $goods_id,
                    'is_delete' => 0,
                    'open_date' => date('Y-m-d'),
                    'start_time' => intval(date('H')),
                ],
                ['!=', 'buy_max', 0],
                ['<', 'buy_max', $item['num']],
            ])->one();
            if ($miaosha_goods) {
                return [
                    'code' => 1,
                    'msg' => "购买数量超过限制！ 商品“" . $item['goods_name'] . '”最多允许购买' . $miaosha_goods->buy_max . '件，请返回重新下单',
                ];
            }
        }
        return null;
    }

    //自定义表单
    private function formData()
    {
        $new_list = [];
        $new_list['is_form'] = Option::get('is_form', $this->store_id, 'admin', 0);
        $form_list = [];
        if ($new_list['is_form'] == 1) {
            $new_list['name'] = Option::get('form_name', $this->store_id, 'admin', '表单信息');
            $form_list = Form::find()->where([
                'store_id' => $this->store_id, 'is_delete' => 0,
            ])->asArray()->all();
            foreach ($form_list as $index => $value) {
                if (in_array($value['type'], ['radio', 'checkbox'])) {
                    $default = str_replace("，", ",", $value['default']);
                    $list = explode(',', $default);
                    $default_list = [];
                    foreach ($list as $k => $v) {
                        $default_list[$k]['name'] = $v;
                        if ($k == 0) {
                            $default_list[$k]['is_selected'] = 1;
                        } else {
                            $default_list[$k]['is_selected'] = 0;
                        }
                    }
                    $form_list[$index]['default_list'] = $default_list;
                }
            }
        }
        $new_list['list'] = $form_list;
        return $new_list;
    }

    //单个商品运费

    /**
     * @param $goods_item object 重新编写的goods_item
     * @param $address object||null 地址
     * @param null $full_cut 单商品运费规则（满件包邮及满额包邮）
     * @return float|int
     */
    private function Express($goods_item, $address, $full_cut = null)
    {
        $express_price = 0;
        if ($address) {
            if ($full_cut) {
                $full_cut = json_decode($full_cut, true);
            } else {
                $full_cut = [
                    'pieces' => 0,
                    'forehead' => 0,
                ];
            }

            if ((empty($full_cut['pieces']) || $goods_item->num < ($full_cut['pieces'] ?: 0)) && (empty($full_cut['forehead']) || $goods_item->price < ($full_cut['forehead'] ?: 0))) {
                $express_price = PostageRules::getExpressPrice($this->store_id, $address['city_id'], $goods_item, $goods_item->num, $address['province_id']);
            }
        }
        return $express_price;
    }
}
