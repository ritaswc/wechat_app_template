<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\api;


use app\models\Express;
use app\models\Goods;
use app\models\GwdBuyList;
use app\models\GwdLikeList;
use app\models\GwdLikeUser;
use app\models\GwdSetting;
use app\models\MsGoods;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtOrderDetail;
use app\models\Store;
use app\models\User;
use Curl\Curl;

class CommonShoppingList
{
    /**
     * 已购清单
     * @param $wechatAccessToken
     * @param $order
     * @param string $orderType
     * @return bool|string
     * @throws \Exception
     */
    public static function buyList($wechatAccessToken, $order, $orderType = '')
    {
        try {
            if (!$wechatAccessToken || !$order || $orderType === '') {
                throw new \Exception('购物清单参数错误:' . 'token:' . $wechatAccessToken . 'orderType:' . $orderType);
            }

            self::isOpen($order->store_id);
            $store = self::getStore($order->store_id);
            $user = self::getUser($order->user_id);
            $address = $order['address_data'] ? json_decode($order['address_data'], true) : [];

            switch ($orderType) {
                // 商城
                case 0:
                    $orderDetails = OrderDetail::find()->where(['order_id' => $order->id])->asArray()->all();
                    $orderDetailPageUrl = '/pages/order-detail/order-detail?id=' . $order->id;
                    $goodPageUrl = '/pages/goods/goods?id=';
                    $productInfo = self::getGoodInfo($orderDetails, $goodPageUrl);
                    break;
                // 秒杀
                case 1:
                    $goodPageUrl = '/pages/miaosha/details/details?id=';
                    $productInfo = self::getMsGoodInfo($order, $goodPageUrl);
                    $orderDetailPageUrl = '/pages/miaosha/order-detail/order-detail?id=' . $order->id;
                    break;
                // 拼团
                case 2:
                    $orderDetails = PtOrderDetail::find()->where(['order_id' => $order->id])->asArray()->all();
                    $orderDetailPageUrl = '/pages/pt/order-details/order-details?id=' . $order->id;
                    $goodPageUrl = '/pages/pt/details/details?id=';
                    $productInfo = self::getPtGoodInfo($orderDetails, $goodPageUrl);
                    break;
                default:
                    throw new \Exception('未知订单类型：' . $orderType);
                    break;
            }

            $postData = [
                "order_list" => [
                    [
                        "order_id" => $order->order_no,
                        "create_time" => $order->addtime,
                        "pay_finish_time" => $order->pay_time,
                        "desc" => $order->remark,
                        "fee" => $order->pay_price * 100,
                        "trans_id" => $order->order_no,
                        "status" => self::getOrderStatus($order),
                        "ext_info" => [
                            "product_info" => [
                                "item_list" => $productInfo
                            ],
                            "express_info" => [
                                "name" => $order->name,
                                "phone" => $order->mobile,
                                "address" => $order->address ? $order->address : '无需物流(支持到店自提)',
                                "price" => $order->express_price * 100,
                                "national_code" => "",
                                "country" => '',
                                "province" => $address['province'] ? $address['province'] : '',
                                "city" => $address['city'] ? $address['city'] : '',
                                "district" => $address['district'] ? $address['district'] : '',
                            ],
                            // 商家信息
                            "brand_info" => [
                                "phone" => $store->contact_tel,
                                "contact_detail_page" => [
                                    "path" => "/pages/index/index"
                                ]
                            ],
                            "payment_method" => $order->pay_type == 1 ? 1 : 2,
                            "user_open_id" => $user->wechat_open_id,
                            "order_detail_page" => [
                                "path" => $orderDetailPageUrl,
                            ]
                        ]
                    ]
                ]
            ];
            \Yii::error($postData);
            \Yii::error('======新增数据=====');
            $api = "https://api.weixin.qq.com/mall/importorder?action=add-order&access_token=" . $wechatAccessToken;
            $curl = new Curl();
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->post($api, json_encode($postData, JSON_UNESCAPED_UNICODE));
            $res = json_decode($curl->response, true);
            if ($res['errcode'] != 0) {
                throw new \Exception('微信好物圈接口错误:' . $res['errmsg']);
            }
            \Yii::warning($res);
            \Yii::warning('好物圈执行完成');
            if ($res->errcode == 0) {
                $gwd = new GwdBuyList();
                $gwd->store_id = $order->store_id;
                $gwd->user_id = $order->user_id;
                $gwd->order_id = $order->id;
                $gwd->addtime = (string)time();
                $gwd->type = $orderType;
                $res = $gwd->save();

                if (!$res) {
                    throw new \Exception('微信好物圈同步到商城失败');
                }
            }
            return $res;

        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     * 更新已购清单商品
     * @param $wechatAccessToken
     * @param $order
     * @param string $orderType
     * @param $status
     * @return string
     */
    public static function updateBuyGood($wechatAccessToken, $order, $orderType = '', $status = '')
    {
        try {

            if (!$wechatAccessToken || !$order || $orderType === '' || $status === '') {
                throw new \Exception('更新购物清单参数错误:' . 'token:' . $wechatAccessToken . 'orderType:' . $orderType . 'status:' . $status);
            }

            self::isOpen($order->store_id);
            if ($order->is_offline) {
                throw new \Exception('到店自提订单没有物流信息,无法更新');
            }

            $prefix = 'mall_';
            switch ($orderType) {
                // 商城
                case 0:
                    $orderDetails = OrderDetail::find()->where(['order_id' => $order->id])->asArray()->all();
                    $orderDetailPageUrl = '/pages/order-detail/order-detail?id=' . $order->id;
                    break;
                // 秒杀
                case 1:
                    $prefix = 'miaosha_';
                    $orderDetailPageUrl = '/pages/miaosha/order-detail/order-detail?id=' . $order->id;
                    break;
                // 拼团
                case 2:
                    $prefix = 'pintuan_';
                    $orderDetails = PtOrderDetail::find()->where(['order_id' => $order->id])->asArray()->all();
                    $orderDetailPageUrl = '/pages/pt/order-details/order-details?id=' . $order->id;
                    break;
                default:
                    throw new \Exception('订单类型未知:' . $orderType);
                    break;
            }

            if ($orderDetails) {
                $goodList = [];
                foreach ($orderDetails as $item) {
                    $arr = [
                        "item_code" => $prefix . $item['goods_id'],
                        "sku_id" => $prefix . $item['goods_id']
                    ];
                    $goodList[] = $arr;
                }
            } else {
                $goodList[] = [
                    "item_code" => $prefix . $order->goods_id,
                    "sku_id" => $prefix . $order->goods_id
                ];
            }
            if (!count($goodList) > 0) {
                throw new \Exception('商品列表信息为空');
            }

            $user = User::find()->where(['id' => $order->user_id])->select('wechat_open_id')->one();
            if (!$user) {
                throw new \Exception('用户不存在');
            }
            $addressData = $order->address_data ? json_decode($order->address_data, true) : [];

            $postData = [
                "order_list" => [
                    [
                        "order_id" => $order->order_no,
                        "trans_id" => $order->order_no,
                        "status" => $status,
                        "ext_info" => [
                            "express_info" => [
                                "name" => $order->name,
                                "phone" => $order->mobile,
                                "address" => $order->address,
                                "price" => $order->express_price * 100,
                                "national_code" => "",
                                "country" => "",
                                "province" => $addressData['province'],
                                "city" => $addressData['city'],
                                "district" => $addressData['district'],
                                "express_package_info_list" => [
                                    [
                                        "express_company_id" => '',
                                        "express_company_name" => $order->express,
                                        "express_code" => $order->express_no,
                                        "ship_time" => $order->send_time,
                                        "express_page" => [
                                            "path" => "/pages/express-detail/express-detail?id=" . $order->id
                                        ],
                                        "express_goods_info_list" => $goodList
                                    ]
                                ]
                            ],
                            "user_open_id" => $user->wechat_open_id,
                            "order_detail_page" => [
                                "path" => $orderDetailPageUrl
                            ]
                        ]
                    ]
                ]
            ];

            \Yii::error($postData);
            \Yii::error('======更新数据=====');
            $api = "https://api.weixin.qq.com/mall/importorder?action=update-order&access_token=" . $wechatAccessToken;
            $curl = new Curl();
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->post($api, json_encode($postData, JSON_UNESCAPED_UNICODE));
            $res = json_decode($curl->response, true);
            if ($res['errcode'] != 0) {
                throw new \Exception('微信好物圈接口错误:' . $res['errmsg']);
            }
            \Yii::warning('更新好物圈商品执行完成');
            return $res;

        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除已买好物圈商品
     * @param $wechatAccessToken
     * @param $order
     * @param string $orderType
     * @param $user
     * @return bool|string
     * @throws \Exception
     */
    public static function destroyBuyGood($wechatAccessToken, $order, $orderType = '', $user)
    {
        if (!$wechatAccessToken || !$order || $orderType === '') {
            throw new \Exception('更新购物清单参数错误:' . 'token:' . $wechatAccessToken . 'orderType:' . $orderType . 'status:' . $status);
        }

        self::isOpen($order->store_id);
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        $postData = [
            "user_open_id" => $user->wechat_open_id,
            "order_id" => $order->order_no
        ];
        try {
            $api = "https://api.weixin.qq.com/mall/deleteorder?access_token=" . $wechatAccessToken;
            $curl = new Curl();
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->post($api, json_encode($postData, JSON_UNESCAPED_UNICODE));
            $res = json_decode($curl->response, true);
            if ($res['errcode'] != 0) {
                throw new \Exception('微信好物圈接口错误:' . $res['errmsg']);
            }
            \Yii::warning('删除好物圈商品执行完成');

            if ($res->errorCode != 0) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     * 想买清单
     * @param $wechatAccessToken
     * @param $cart
     * @param $user
     * @return bool|string
     * @throws \Exception
     */
    public static function cartList($wechatAccessToken, $cart, $user)
    {
        try {
            if (!$wechatAccessToken || !$cart) {
                throw new \Exception('想买清单参数错误：' . 'token:' . $wechatAccessToken);
            }

            /** @var Goods $good */
            $good = Goods::find()->with(['cats', 'goodsPicList'])->where(['id' => $cart->goods_id])->one();
            if (!$good) {
                throw new \Exception('商品不存在');
            }

            if (\Yii::$app->controller->module->id != 'mch') {
                $setting = GwdSetting::find()->where(['store_id' => $user->store_id])->one();
                if (!$setting || $setting->status != 1) {
                    throw new \Exception('功能未开启');
                }
            }
            \Yii::warning($good);
            \Yii::warning('============');

            // 商品分类
            $cats = [];

            if ($good->mch_id > 0) {
                $prefix = 'mch_';
                $cList = $good->mchCats;
            } else {
                $prefix = 'mall_';
                $cList = $good->cats;
            }
            if ($cList) {
                foreach ($cList as $item) {
                    $cats[] = $item->name;
                }
            }
            if (!$cats) {
                throw new \Exception('商品分类数组为空');
            }


            // 商品轮播图
            $pics = [];
            if ($good->goodsPicList) {
                foreach ($good->goodsPicList as $item) {
                    $pics[] = $item->pic_url;
                }
            }
            $pics = $pics ? $pics : [$good->cover_pic];

            if (!$pics) {
                throw new \Exception('商品轮播图数组为空');
            }

            // 商品规格信息
            $attrGroups = $good->getAttrGroupList();
            $selectAttrInfo = [];
            $cartAttr = $cart->attr ? json_decode($cart->attr) : [];

            foreach ($cartAttr as $item) {
                foreach ($attrGroups as $item2) {
                    foreach ($item2->attr_list as $item3) {
                        if ($item == $item3->attr_id) {
                            $selectAttrInfo[] = [
                                'name' => $item2->attr_group_name,
                                'value' => $item3->attr_name
                            ];
                        }
                    }
                }
            }
            $postData = [
                "user_open_id" => $user->wechat_open_id,
                "sku_product_list" => [
                    [
                        "item_code" => $prefix . $good->id,
                        "title" => $good->name,
                        "desc" => "",
                        "category_list" => $cats,
                        "image_list" => $pics,
                        "src_wxapp_path" => "/pages/goods/goods?id=" . $good->id,
                        "attr_list" => $selectAttrInfo ? $selectAttrInfo : [],
                        // "version" => 100,
                        "update_time" => $cart->addtime,
                        "sku_info" => [
                            "sku_id" => $good->id,
                            "price" => $good->price * 100,
                            "original_price" => $good->original_price * 100,
                            "status" => $good->status ? 1 : 2,
                            "sku_attr_list" => $selectAttrInfo ? $selectAttrInfo : [],
                            // "version" => 1200,
//                        "poi_list" => [
//                            [
//                                "poi_id" => "qqmap_12810183469461025708xxx",
//                                "radius" => 4
//                            ],
//                            [
//                                "poi_id" => "qqmap_12810183469461025798xxx",
//                                "radius" => 3
//                            ]
//                        ]
                        ]
                    ]
                ]
            ];

            $api = "https://api.weixin.qq.com/mall/addshoppinglist?access_token=" . $wechatAccessToken;
            $curl = new Curl();
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->post($api, json_encode($postData, JSON_UNESCAPED_UNICODE));
            $res = json_decode($curl->response, true);
            if ($res['errcode'] != 0) {
                throw new \Exception('微信好物圈接口错误:' . $res['errmsg']);
            }
            \Yii::warning('想买清单执行完成');

            if ($res->errcode == 0) {
                $gwd = GwdLikeList::find()->where([
                    'good_id' => $good->id,
                    'store_id' => $user->store_id,
                    'is_delete' => 0,
                    'type' => GwdLikeList::TYPE_STORE
                ])->one();
                if (!$gwd) {
                    $gwd = new GwdLikeList();
                    $gwd->store_id = $user->store_id;
                    $gwd->good_id = $good->id;
                    $gwd->addtime = (string)time();
                    $gwd->type = 0; // 0.商城商品
                    $res = $gwd->save();
                }

                if (!$res) {
                    throw new \Exception('想买好物圈同步到商城失败x01');
                }

                $gwdLike = GwdLikeUser::find()->where(['user_id' => $user->id, 'like_id' => $gwd->id, 'is_delete' => 0])->one();
                if (!$gwdLike) {
                    $gwdUser = new GwdLikeUser();
                    $gwdUser->user_id = $user->id;
                    $gwdUser->like_id = $gwd->id;
                    $res = $gwdUser->save();

                    if (!$res) {
                        throw new \Exception('想买好物圈同步到商城失败x02');
                    }
                }
            }
            return $res;

        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除想买清单商品
     * @param $wechatAccessToken
     * @param $cartIdList
     * @param $storeId
     * @return array
     * @throws \Exception
     */
    public static function destroyCartGood($wechatAccessToken, $data, $storeId)
    {
        try {

            if (!$wechatAccessToken || !$storeId) {
                throw new \Exception('token:' . $wechatAccessToken . 'storeId:' . $storeId);
            }

            if (\Yii::$app->controller->module->id != 'mch') {
                $setting = GwdSetting::find()->where(['store_id' => $storeId])->one();
                if (!$setting || $setting->status != 1) {
                    throw new \Exception('功能未开启');
                }
            }

            $allRes = [];
            foreach ($data as $item) {
                $user = User::find()->where(['id' => $item['user_id']])->select('wechat_open_id')->one();
                if (!$user) {
                    throw new \Exception('用户不存在');
                }
                $goods = Goods::findOne($item['good_id']);
                if ($goods->mch_id > 0) {
                    $prefix = 'mch_';
                } else {
                    $prefix = 'mall_';
                }

                $postData = [
                    "user_open_id" => $user->wechat_open_id,
                    "sku_product_list" => [
                        [
                            "item_code" => $prefix . $item['good_id'],
                            "sku_id" => $prefix . $item['good_id'],
                        ]
                    ]
                ];

                $api = "https://api.weixin.qq.com/mall/deleteshoppinglist?access_token=" . $wechatAccessToken;
                $curl = new Curl();
                $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
                $curl->post($api, json_encode($postData, JSON_UNESCAPED_UNICODE));
                $res = json_decode($curl->response, true);
                if ($res['errcode'] != 0) {
                    throw new \Exception('微信好物圈接口错误:' . $res['errmsg']);
                }
                $allRes[] = $res;
                \Yii::warning('删除想买清单商品执行完成');

                $gwdLike = GwdLikeList::find()->where([
                    'good_id' => $item['good_id'],
                    'is_delete' => 0,
                    'store_id' => $storeId
                ])->one();

                if (!$gwdLike) {
                    throw new \Exception('无关联用户记录');
                }

                $gwd = GwdLikeUser::find()->where(['like_id' => $gwdLike->id, 'user_id' => $item['user_id'], 'is_delete' => 0])->one();
                if ($gwd) {
                    $gwd->is_delete = 1;
                    $res = $gwd->save();

                    if (!$res) {
                        \Yii::error('gwd_like_list_user表关联删除失败');
                    }
                }
            }

            return $allRes;

        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     * 商城商品信息
     * @param $orderDetails
     * @param $goodPageUrl
     * @param $type
     * @return array
     */
    private static function getGoodInfo($orderDetails, $goodPageUrl)
    {
        $productInfo = [];
        foreach ($orderDetails as $item) {
            $good = Goods::find()->with(['cats'])->where(['id' => $item['goods_id']])->one();

            $attr = [];
            if (isset($item['attr']) && $item['attr']) {
                $attr = json_decode($item['attr'], true);
            }
            // 商品规格信息
            $stockAttrInfo = [];
            foreach ($attr as $item2) {
                $stockAttrInfo[] = [
                    'attr_name' => [
                        'name' => $item2['attr_group_name']
                    ],
                    'attr_value' => [
                        'name' => $item2['attr_name']
                    ]
                ];
            }
            $cats = [];
            if ($good->cats) {
                foreach ($good->cats as $item3) {
                    $cats[] = $item3->name;
                }
            }

            $itemList = [
                "item_code" => 'mall_' . $item['goods_id'],
                "sku_id" => 'mall_' . $item['goods_id'],
                "amount" => $item['num'],
                "total_fee" => $item['total_price'] * 100,
                "thumb_url" => $item['pic'],
                "title" => $good->name,
                "desc" => "",
                "unit_price" => $good->price * 100,
                "original_price" => $good->original_price * 100,
                "stock_attr_info" => $stockAttrInfo,
                "category_list" => $cats,
                "item_detail_page" => [
                    "path" => $goodPageUrl . $item['goods_id']
                ],
                // 仅支持到店自提的商品
//                "poi_list" => [
//                    [
//                        "poi_id" => "qqmap_12810183469461025708xxx",
//                        "radius" => 4
//                    ],
//                    [
//                        "poi_id" => "qqmap_12810183469461025798xxx",
//                        "radius" => 3
//                    ]
//                ]
            ];
            $productInfo[] = $itemList;
        }

        return $productInfo;
    }

    /**
     * 拼团商品信息
     * @param $orderDetails
     * @param $goodPageUrl
     * @param $type
     * @return array
     */
    private static function getPtGoodInfo($orderDetails, $goodPageUrl)
    {
        $productInfo = [];
        foreach ($orderDetails as $item) {
            $good = PtGoods::find()->with('cat')->where(['id' => $item['goods_id']])->one();
            $attr = [];
            if (isset($item['attr']) && $item['attr']) {
                $attr = json_decode($item['attr'], true);
            }
            // 商品规格信息
            $stockAttrInfo = [];
            foreach ($attr as $item2) {
                $stockAttrInfo[] = [
                    'attr_name' => [
                        'name' => $item2['attr_group_name']
                    ],
                    'attr_value' => [
                        'name' => $item2['attr_name']
                    ]
                ];
            }
            $cats = [];
            if ($good->cat) {
                $cats[] = $good->cat->name;
            }

            $itemList = [
                "item_code" => 'pintuan_' . $item['goods_id'],
                "sku_id" => 'pintuan_' . $item['goods_id'],
                "amount" => $item['num'],
                "total_fee" => $item['total_price'] * 100,
                "thumb_url" => $item['pic'],
                "title" => $good->name,
                "desc" => "",
                "unit_price" => $good->price * 100,
                "original_price" => $good->original_price * 100,
                "stock_attr_info" => $stockAttrInfo,
                "category_list" => $cats,
                "item_detail_page" => [
                    "path" => $goodPageUrl . $item['goods_id']
                ],
                // 仅支持到店自提的商品
//                "poi_list" => [
//                    [
//                        "poi_id" => "qqmap_12810183469461025708xxx",
//                        "radius" => 4
//                    ],
//                    [
//                        "poi_id" => "qqmap_12810183469461025798xxx",
//                        "radius" => 3
//                    ]
//                ]
            ];
            $productInfo[] = $itemList;
        }

        return $productInfo;
    }

    /**
     * 秒杀商品信息
     * @param $order
     * @param $goodPageUrl
     * @return array
     */
    private static function getMsGoodInfo($order, $goodPageUrl)
    {
        $msGood = MsGoods::findOne($order->goods_id);

        $attr = [];
        if (isset($order->attr) && $order->attr) {
            $attr = json_decode($order->attr, true);
        }
        // 商品规格信息
        $stockAttrInfo = [];
        foreach ($attr as $item2) {
            $stockAttrInfo[] = [
                'attr_name' => [
                    'name' => $item2['attr_group_name']
                ],
                'attr_value' => [
                    'name' => $item2['attr_name']
                ]
            ];
        }

        $productInfo[] = [
            "item_code" => 'miaosha_' . $order->goods_id,
            "sku_id" => 'miaosha_' . $order->goods_id,
            "amount" => $order->num,
            "total_fee" => $order->pay_price * 100,
            "thumb_url" => $order->pic,
            "title" => $msGood->name,
            "desc" => "",
            "unit_price" => $msGood->original_price * 100,
            "original_price" => $msGood->original_price * 100,
            "stock_attr_info" => $stockAttrInfo,
            "category_list" => ['秒杀商品'],
            "item_detail_page" => [
                "path" => $goodPageUrl . $order->goods_id
            ],
            // 仅支持到店自提的商品
//                "poi_list" => [
//                    [
//                        "poi_id" => "qqmap_12810183469461025708xxx",
//                        "radius" => 4
//                    ],
//                    [
//                        "poi_id" => "qqmap_12810183469461025798xxx",
//                        "radius" => 3
//                    ]
//                ]
        ];

        return $productInfo;
    }

    private static function isOpen($storeId)
    {
        if (\Yii::$app->controller->module->id != 'mch') {
            $setting = GwdSetting::find()->where(['store_id' => $storeId])->one();
            if (!$setting || $setting->status != 1) {
                throw new \Exception('功能未开启');
            }
        }
    }

    private static function getUser($userId)
    {
        $user = User::find()->where(['id' => $userId])->select('wechat_open_id')->one();
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        return $user;
    }

    private static function getStore($storeId)
    {
        $store = Store::findOne($storeId);
        if (!$store) {
            throw new \Exception('商城不存在');
        }

        return $store;
    }

    public static function getOrderStatus($order)
    {
        $status = 3;
        if (isset($order->is_send) && $order->is_send == 1) {
            $status = 4;
        }
        if (isset($order->is_confirm) && $order->is_confirm == 1) {
            $status = 100;
        }

        if (isset($order->is_cancel) && $order->is_cancel == 1) {
            $status = 5;
        }

        return $status;
    }
}