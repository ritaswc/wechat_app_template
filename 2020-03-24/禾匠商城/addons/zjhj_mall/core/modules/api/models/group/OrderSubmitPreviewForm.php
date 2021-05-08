<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:48
 */

namespace app\modules\api\models\group;

use app\models\Address;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\common\CommonGoods;
use app\models\FreeDeliveryRules;
use app\models\PostageRules;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Shop;
use app\models\Store;
use app\modules\api\models\ApiModel;
use app\modules\api\models\OrderData;
use app\models\PtGoodsDetail;
use app\models\TerritorialLimitation;
use app\models\PtSetting;

class OrderSubmitPreviewForm extends ApiModel
{
    public $store_id;
    public $user_id;

    public $address_id;

    public $cart_id_list;
    public $goods_info;

    public $type;

    public $longitude;
    public $latitude;

    public function rules()
    {
        return [
            [['cart_id_list', 'goods_info', 'type'], 'string'],
            [['address_id',], 'integer'],
            [['longitude', 'latitude'], 'trim']
        ];
    }

    public function search()
    {
        $store = Store::findOne($this->store_id);
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $res = $this->getDataByGoodsInfo($this->goods_info, $store);
        $res['data']['send_type'] = $store->send_type;

        //包邮规则
        if ($res['data']['express_price'] != 0) {
            $free = FreeDeliveryRules::find()->where(['store_id' => $this->store_id])->asArray()->all();
            foreach ($free as $k => $v) {
                $city = json_decode($v['city'], true);
                foreach ($city as $v1) {
                    if ($res['data']['address']['city_id'] == $v1['id'] && $res['data']['total_price'] >= $v['price']) {
                        $res['data']['express_price'] = 0;
                        break;
                    }
                }
            }
        }
        if ($res['code'] == 0) {
            $shopArr = $this->getShopList();
            $res['data']['shop_list'] = $shopArr['list'];
            $res['data']['shop'] = $shopArr['shop'];
        }
        $lastOrder = PtOrder::find()->where(['user_id' => $this->user_id, 'offline' => 2])->orderBy(['id' => SORT_DESC])->one();
        $res['data']['name'] = $lastOrder->name;
        $res['data']['mobile'] = $lastOrder->mobile;

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
            } else {
                $city_id = [];  //限制的地区ID
                $detail = json_decode($area->detail);
                foreach ($detail as $key => $value) {
                    foreach ($value->province_list as $key2 => $value2) {
                        $city_id[] = $value2->id;
                    }
                }
                $setting = PtSetting::findOne([
                    'store_id' => $this->store_id,
                    'is_area' => 1,
                ]);
                if ($setting) {
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
            }
        }

        return $res;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * 获取店铺列表
     */
    private function getShopList()
    {
        $list = Shop::find()->select(['address', 'mobile', 'id', 'name', 'longitude', 'latitude', 'is_default'])
            ->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        $distance = array();
        $shop = null;
        foreach ($list as $index => $item) {
            $list[$index]['distance'] = -1;
            if ($item['longitude'] && $this->longitude) {
                $from = [$this->longitude, $this->latitude];
                $to = [$item['longitude'], $item['latitude']];
                $list[$index]['distance'] = $this->get_distance($from, $to, false, 2);
            }
            $distance[] = $list[$index]['distance'];
            if ($item['is_default'] == 1) {
                $shop = $item;
            }
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
        return [
            'list' => $list,
            'shop' => $shop
        ];
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from  [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    function get_distance($from, $to, $km = true, $decimal = 2)
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
     * 团购确认订单页展示数据获取
     * @param string $goods_info
     * JSON,eg.{"goods_id":"22","attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":3,"attr_name":"橙色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":7,"attr_name":"L"}],"num":1}
     */
    private function getDataByGoodsInfo($goods_info, $store)
    {
        $goods_info = json_decode($goods_info);
        $goods = PtGoods::findOne([
            'id' => $goods_info->goods_id,
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'status' => 1,
        ]);

        $ptGoods = $goods; // TODO
        if ($goods_info->group_id) {
            $goods = PtGoodsDetail::find()->where(['store_id' => $this->getCurrentStoreId(), 'id' => $goods_info->group_id])->one();

        }

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
//         $goods_attr_info = $goods->getAttrInfo($attr_id_list, $goods_info->group_id);

        $goodsData = [
            'attr' => $goods['attr'],
            'price' => $ptGoods['price'],
            'is_level' => $goods['is_level']
        ];

        $goods_attr_info = CommonGoods::currentGoodsAttr($goodsData, $attr_id_list, [
            'type' => 'PINTUAN',
            'single_price' => $ptGoods['original_price'],
            'order_type' => $this->type
        ]);


        $attr_list = Attr::find()->alias('a')
            ->select('ag.attr_group_name,a.attr_name')
            ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
            ->where(['a.id' => $attr_id_list])
            ->asArray()->all();

        $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $ptGoods->cover_pic : $ptGoods->cover_pic;

        $single_price = 0;
        if ($this->type == 'GROUP_BUY' || $this->type == 'GROUP_BUY_C') {      // 拼团
            $single_price = doubleval(empty($goods_attr_info['goods_price']) ? $ptGoods->price : $goods_attr_info['goods_price']);
        } elseif ($this->type == 'ONLY_BUY') {  // 单独购买
            $single_price = doubleval(empty($goods_attr_info['single_price']) ? $ptGoods->original_price : $goods_attr_info['single_price']);
            $goods_attr_info['level_price'] = $single_price;
        }

        $goods_item = (object)[
            'goods_id' => $ptGoods->id,
            'goods_name' => $ptGoods->name,
            'goods_pic' => $goods_pic,
            'num' => $goods_info->num,
            'price' => sprintf('%.2f', ($single_price * $goods_info->num)),
            'single_price' => sprintf('%.2f', $single_price),
            'attr_list' => $attr_list,
            'payment' => $ptGoods->payment,
            'level_price' => sprintf('%.2f', ($goods_attr_info['level_price'] * $goods_info->num)),
            'is_level' => $goods_attr_info['is_level'],
        ];

        $total_price += $goods_item->price;

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
        $express_price = 0;
        if ($address) {
            $express_price = PostageRules::getExpressPrice($this->store_id, $address['city_id'], $ptGoods, $goods_info->num, $address['province_id']);
        }

        if ($this->type == 'ONLY_BUY') {
            $colonel = 0;
        } else {
            if ($this->type == 'GROUP_BUY') {
                $colonel = $ptGoods->colonel;

                if ($goods_info->group_id) {
                    $detail = PtGoodsDetail::findOne([
                        'id' => $goods_info->group_id,
                        'store_id' => $this->store_id,
                    ]);
                    $colonel = $detail->colonel;
                }
            } else {
                $colonel = 0;
            }
            $orderNum = PtOrder::find()
                ->alias('o')
                ->select([
                    'o.*',
                ])
                ->andWhere(['o.user_id' => $this->user_id, 'o.is_delete' => 0, 'o.is_group' => 1])
                ->andWhere(['OR', ['o.is_pay' => 1], ['o.pay_type' => 2]])
                ->andWhere(['OR', ['o.status' => 2], ['o.status' => 3]])
                ->leftJoin(['od' => PtOrderDetail::tableName()], 'od.order_id = o.id')
                ->andWhere(['od.goods_id' => $ptGoods->id])
                ->andWhere(['!=', 'o.order_no', 'robot'])
                ->count();
            if (!empty($ptGoods->buy_limit) && $ptGoods->buy_limit <= $orderNum) {
                return [
                    'code' => 1,
                    'msg' => '您已超过该商品购买次数',
                ];
            }
            if ($ptGoods->one_buy_limit > 0) {
                if ($goods_item->num > $ptGoods->one_buy_limit) {
                    return [
                        'code' => 1,
                        'msg' => '您已超过该商品可购买数量'
                    ];
                }
            }
        }
        //商品支付方式
        $is_payment = json_decode($goods_item->payment, true);
        $pay_type_list = OrderData::getPayType($this->store_id, $is_payment);

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'total_price' => sprintf('%.2f', $total_price),
                'goods_info' => $goods_info,
                'list' => [
                    $goods_item
                ],
                'address' => $address,
                'express_price' => $express_price,
                'colonel' => $colonel,
                'pay_type_list' => $pay_type_list
            ],
        ];
    }


    /**
     * 单独购买确认订单展示页数据获取
     * @param string $goods_info
     * JSON,eg.{"goods_id":"22","attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":3,"attr_name":"橙色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":7,"attr_name":"L"}],"num":1}
     */
    private function getDataByGoodsInfoOnOnly($goods_info, $store)
    {
        $goods_info = json_decode($goods_info);
        $goods = PtGoods::findOne([
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
        $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->cover_pic : $goods->cover_pic;
        $goods_item = (object)[
            'goods_id' => $goods->id,
            'goods_name' => $goods->name,
            'goods_pic' => $goods_pic,
            'num' => $goods_info->num,
            'price' => doubleval(empty($goods_attr_info['single']) ? $goods->original_price : $goods_attr_info['single']) * $goods_info->num,
            'attr_list' => $attr_list,
        ];

        $total_price += $goods_item->price;
        $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')->where([
            'id' => $this->address_id,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ])->asArray()->one();
        if (!$address) {
            $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')
                ->where([
                    'store_id' => $this->store_id,
                    'user_id' => $this->user_id,
                    'is_delete' => 0,
                ])->orderBy('is_default DESC,addtime DESC')->asArray()->one();
        }

        $express_price = 0;
        if ($address) {
            $express_price = PostageRules::getExpressPrice($this->store_id, $address['city_id'], $goods, $goods_info->num, $address['province_id']);
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'total_price' => $total_price,
                'goods_info' => $goods_info,
                'list' => [
                    $goods_item
                ],
                'address' => $address,
                'express_price' => $express_price,
                'colonel' => 0,
            ],
        ];
    }
}
