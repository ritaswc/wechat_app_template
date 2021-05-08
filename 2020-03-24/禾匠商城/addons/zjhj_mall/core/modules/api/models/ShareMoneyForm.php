<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/5/22
 * Time: 15:53
 */

namespace app\modules\api\models;

use app\models\ActivityMsgTpl;
use app\models\BargainSetting;
use app\models\common\CommonGoods;
use app\models\Goods;
use app\models\GoodsShare;
use app\models\MchPlugin;
use app\models\MchSetting;
use app\models\MiaoshaGoods;
use app\models\Model;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsSetting;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderShare;
use app\models\PtGoodsDetail;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtSetting;
use app\models\Setting;
use app\models\User;
use app\models\YyGoods;
use app\models\YyOrder;
use app\models\YySetting;

class ShareMoneyForm extends ApiModel
{
    public $order;
    public $order_type;

    public function setData()
    {
        $setting = Setting::findOne(['store_id' => $this->order->store_id]);
        if (!$setting || $setting->level == 0) {
            \Yii::warning('未开启分销');
            return false;
        }
        if (isset($this->order->mch_id) && $this->order->mch_id > 0) {
            $mchPlugin = MchPlugin::findOne(['mch_id' => $this->order->mch_id, 'store_id' => $this->order->store_id]);
            if (!$mchPlugin || $mchPlugin->is_share == 0) {
                \Yii::warning('总平台未给多商户开启分销');
                return false;
            }
            $mchSetting = MchSetting::findOne(['mch_id' => $this->order->mch_id]);
            if (!$mchSetting) {
                \Yii::warning('多商户未开启分销x01');
                return false;
            }
            if ($mchSetting->is_share == 0) {
                \Yii::warning('多商户未开启分销x02');
                return false;
            }
        }

        if (isset($this->order->type) && $this->order->type == 2) {
            $bargainSetting = BargainSetting::findOne(['store_id' => $this->order->store_id]);
            if ($bargainSetting->is_share == 0) {
                \Yii::warning('砍价商品分销未开启');
                return false;
            }
        }

        $orderDetail = $this->getDetail();
        if (!$orderDetail) {
            \Yii::warning('订单详情不存在');
            return false;
        }
        $user = User::findOne($this->order->user_id);
        if (!$user) {
            \Yii::warning('订单用户不存在');
            return false;
        }
        $order = $this->order;

        $cParent1 = -1;
        $cParent2 = -1;
        $cParent3 = -1;
        if ($this->order_type == 1 || $this->order_type == 0) {
            /* @var $orderShare Order */
            $orderShare = $this->order;

            $cParent1 = $user->parent_id;
            $orderShare->parent_id = $cParent1;
            if ($user->parent_id) {
                $parent = User::findOne($user->parent_id);//上级
                $cParent2 = $parent->parent_id;
                $orderShare->parent_id_1 = $cParent2;
                if ($parent->parent_id) {
                    $parent_1 = User::findOne($parent->parent_id);//上上级
                    $cParent3 = $parent_1->parent_id;
                    $orderShare->parent_id_2 = $cParent3;
                } else {
                    $orderShare->parent_id_2 = -1;
                }
            } else {
                $orderShare->parent_id_1 = -1;
                $orderShare->parent_id_2 = -1;
            }
        } else {
            if ($this->order_type == 2) {
                $type = 0;
            } else {
                $type = 1;
            }
            $orderShare = OrderShare::findOne(['order_id' => $order->id, 'type' => $type, 'is_delete' => 0, 'store_id' => $order->store_id]);
            if (!$orderShare) {
                $orderShare = new OrderShare();
                $orderShare->order_id = $order->id;
                $orderShare->store_id = $order->store_id;
                $orderShare->is_delete = 0;
                $orderShare->user_id = $order->user_id;
            }
            $orderShare->version = hj_core_version();
            $orderShare->type = $type;

            $orderShare->parent_id_1 = $cParent1 = $user->parent_id;
            if ($user->parent_id) {
                $parent = User::findOne($user->parent_id);//上级
                $orderShare->parent_id_2 = $cParent2 = $parent->parent_id;
                if ($parent->parent_id) {
                    $parent_1 = User::findOne($parent->parent_id);//上上级
                    $orderShare->parent_id_3 = $cParent3 = $parent_1->parent_id;
                } else {
                    $orderShare->parent_id_3 = -1;
                }
            } else {
                $orderShare->parent_id_2 = -1;
                $orderShare->parent_id_3 = -1;
            }
        }

        $share_commission_money_first = 0;//一级分销总佣金
        $share_commission_money_second = 0;//二级分销总佣金
        $share_commission_money_third = 0;//三级分销总佣金
        foreach ($orderDetail as $item) {
            $item_price = doubleval($item['price']);
            if ($item['individual_share'] == 1) {
                $rate_first = doubleval($item['share_commission_first']);
                $rate_second = doubleval($item['share_commission_second']);
                $rate_third = doubleval($item['share_commission_third']);
                $shareType = $item['share_type'];
            } else {
                if (isset($item['mch_id']) && $item['mch_id'] > 0) {
                    continue;
                }
                $rate_first = doubleval($setting->first);
                $rate_second = doubleval($setting->second);
                $rate_third = doubleval($setting->third);
                $shareType = $setting->price_type;
            }
            if ($shareType == 1) {
                $share_commission_money_first += $rate_first * $item['num'];
                $share_commission_money_second += $rate_second * $item['num'];
                $share_commission_money_third += $rate_third * $item['num'];
            } else {
                $share_commission_money_first += $item_price * $rate_first / 100;
                $share_commission_money_second += $item_price * $rate_second / 100;
                $share_commission_money_third += $item_price * $rate_third / 100;
            }
        }

        // 如果开启自购返利 一级是自己
        if ($setting->is_rebate == 1 && $user->is_distributor == 1) {
            $cParent1 = $user->id;
            $cParent2 = $user->parent_id;
            $cParent3 = $parent ? $parent->parent_id : -1;

            $orderShare->rebate = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
            $orderShare->first_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
            $orderShare->second_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
            $orderShare->third_price = 0;
        } else {
            $orderShare->rebate = 0;
            $orderShare->first_price = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
            $orderShare->second_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
            $orderShare->third_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
        }

        // 发送佣金模板消息
        if ($cParent1 > 0 && $share_commission_money_first >= 0.01) {
            $tplMsg = new ActivityMsgTpl($cParent1, 'SHARE');
            $tplMsg->accountChangeMsg('有用户下单，预计可得佣金' . sprintf('%.2f', $share_commission_money_first), '分销佣金');
        }
        if ($cParent2 > 0 && $share_commission_money_second >= 0.01) {
            $tplMsg = new ActivityMsgTpl($cParent2, 'SHARE');
            $tplMsg->accountChangeMsg('有用户下单，预计可得佣金' . sprintf('%.2f', $share_commission_money_second), '分销佣金');
        }

        if ($cParent3 > 0 && $share_commission_money_third >= 0.01) {
            $tplMsg = new ActivityMsgTpl($cParent3, 'SHARE');
            $tplMsg->accountChangeMsg('有用户下单，预计可得佣金' . sprintf('%.2f', $share_commission_money_third), '分销佣金');
        }

        $res = $orderShare->save();
        if (!$res) {
            \Yii::warning('分销订单生成失败');
        }

        \Yii::warning('分销订单生成成功');
        return $res;
    }


    private function getDetail()
    {
        if ($this->order_type == 0) {
            return $this->getOrderDetail();
        } elseif ($this->order_type == 1) {
            return $this->getMsOrderDetail();
        } elseif ($this->order_type == 2) {
            return $this->getPtOrderDetail();
        } elseif ($this->order_type == 3) {
            return $this->getYyOrderDetail();
        } else {
            return false;
        }
    }

    private function getOrderDetail()
    {
        /* @var $order Order */
        $order = $this->order;
        /* @var $list OrderDetail */
        $list = OrderDetail::find()->where(['is_delete' => 0, 'order_id' => $order->id])->all();

        $newList = [];
        foreach ($list as $value) {
            $goods = $value->goods;
            $buyAttrList = \Yii::$app->serializer->decode($value['attr']);

            if ($goods['attr_setting_type'] === 1) {
                $attrIdArr2 = [];
                foreach ($buyAttrList as $attrListItem2) {
                    $attrIdArr2[] = $attrListItem2['attr_id'];
                }

                $goodsData = [
                    'attr' => $goods['attr'],
                    'price' => $goods['price'],
                    'is_level' => $goods['is_level'],
                ];
                $res = CommonGoods::currentGoodsAttr($goodsData, $attrIdArr2);

                $newItem = [
                    'individual_share' => $goods['individual_share'],
                    'share_commission_first' => $res['share_commission_first'],
                    'share_commission_second' => $res['share_commission_second'],
                    'share_commission_third' => $res['share_commission_third'],
                    'share_type' => $goods['share_type'],
                    'num' => $value['num'],
                    'price' => $value['total_price'],
                    'mch_id' => $goods['mch_id']
                ];
            } else {
                $newItem = [
                    'individual_share' => $goods['individual_share'],
                    'share_commission_first' => $goods['share_commission_first'],
                    'share_commission_second' => $goods['share_commission_second'],
                    'share_commission_third' => $goods['share_commission_third'],
                    'share_type' => $goods['share_type'],
                    'num' => $value['num'],
                    'price' => $value['total_price'],
                    'mch_id' => $goods['mch_id']
                ];
            }

            array_push($newList, $newItem);
        }
        return $newList;
    }

    private function getMsOrderDetail()
    {
        /* @var $order MsOrder */
        $order = $this->order;

        $ms_setting = MsSetting::findOne(['store_id' => $order->store_id]);
        if (!$ms_setting || $ms_setting->is_share == 0) {
            return false;
        }

        $date = date('Y-m-d', $order->addtime);
        $hour = date('H', $order->addtime);

        $miaoshaGoods = MiaoshaGoods::find()->where([
            'goods_id' => $order->goods_id,
            'store_id' => $this->getCurrentStoreId(),
            'open_date' => $date,
            'start_time' => $hour,
            'is_delete' => Model::IS_DELETE_FALSE
        ])->asArray()->one();

        $msGoods = MsGoods::find()->where(['id' => $order->goods_id])->select('id,original_price')->one();
        $miaoshaGoods['price'] = $msGoods->original_price;

        $goodsShare = GoodsShare::find()->where(['relation_id' => $miaoshaGoods['id'], 'type' => GoodsShare::SHARE_GOODS_TYPE_MS])->asArray()->one();

        $buyAttrList = \Yii::$app->serializer->decode($order['attr']);
        $newList = [];
        if ((int)$goodsShare['attr_setting_type'] === 1) {

            $attrIdArr2 = [];
            foreach ($buyAttrList as $attrListItem2) {
                $attrIdArr2[] = $attrListItem2['attr_id'];
            }

            $goodsData = [
                'attr' => $miaoshaGoods['attr'],
                'price' => $msGoods['original_price'],
                // 'is_level' => $msGoods['is_discount'],
                'is_level' => $miaoshaGoods['is_level'],
            ];
            $res = CommonGoods::currentGoodsAttr($goodsData, $attrIdArr2, [
                'type' => 'MIAOSHA',
                'original_price' => $msGoods['original_price']
            ]);

            $newItem = [
                'individual_share' => $goodsShare['individual_share'],
                'share_commission_first' => $res['share_commission_first'],
                'share_commission_second' => $res['share_commission_second'],
                'share_commission_third' => $res['share_commission_third'],
                'share_type' => $goodsShare['share_type'],
                'num' => $order->num,
                'price' => doubleval($order->pay_price - $order->express_price),
            ];

        } else {
            $newItem = [
                'individual_share' => $goodsShare['individual_share'],
                'share_commission_first' => $goodsShare['share_commission_first'],
                'share_commission_second' => $goodsShare['share_commission_second'],
                'share_commission_third' => $goodsShare['share_commission_third'],
                'share_type' => $goodsShare['share_type'],
                'num' => $order->num,
                'price' => doubleval($order->pay_price - $order->express_price)
            ];
        }

        array_push($newList, $newItem);
        return $newList;
    }

    private function getPtOrderDetail()
    {
        /* @var $order PtOrder */
        $order = $this->order;
        $pt_setting = PtSetting::findOne(['store_id' => $order->store_id]);
        if (!$pt_setting || $pt_setting->is_share == 0) {
            \Yii::warning('拼团未开启分销');
            return false;
        }

        // 阶梯团ID
        $classGroupId = $order->class_group;

        /* @var $list PtOrderDetail */
        $list = PtOrderDetail::find()->where(['is_delete' => 0, 'order_id' => $order->id])->all();
        $newList = [];
        foreach ($list as $value) {
            $share = $value->share;

            // 阶梯团有不同分销佣金设置
            if ($classGroupId > 0) {
                $share = GoodsShare::find()->where([
                    'relation_id' => $classGroupId,
                    'type' => GoodsShare::SHARE_GOODS_TYPE_PT_STANDARD
                ])->one();
            }

            $buyAttrList = \Yii::$app->serializer->decode($value['attr']);

            if ($share['attr_setting_type'] === 1) {
                $goods = $value->goods;
                $ptGoods = $value->goods;
                if ($classGroupId > 0) {
                    $goods = PtGoodsDetail::findOne($classGroupId);
                }

                $attrIdArr2 = [];
                foreach ($buyAttrList as $attrListItem2) {
                    $attrIdArr2[] = $attrListItem2['attr_id'];
                }

                $goodsData = [
                    'attr' => $goods['attr'],
                    'price' => $ptGoods['price'],
                    'is_level' => $ptGoods['is_level'],
                ];

                $otherData = [
                    'type' => 'PINTUAN',
                    'single_price' => $ptGoods['original_price'],
                    'order_type' => $order->is_group == 0 ? 'ONLY_BUY' : ''
                ];
                $res = CommonGoods::currentGoodsAttr($goodsData, $attrIdArr2, $otherData);

                $newItem = [
                    'individual_share' => $share['individual_share'],
                    'share_commission_first' => $res['share_commission_first'],
                    'share_commission_second' => $res['share_commission_second'],
                    'share_commission_third' => $res['share_commission_third'],
                    'share_type' => $share['share_type'],
                    'num' => $value['num'],
                    'price' => doubleval($order->pay_price - $order->express_price)
                ];

            } else {
                $newItem = [
                    'individual_share' => $share['individual_share'],
                    'share_commission_first' => $share['share_commission_first'],
                    'share_commission_second' => $share['share_commission_second'],
                    'share_commission_third' => $share['share_commission_third'],
                    'share_type' => $share['share_type'],
                    'num' => $value['num'],
                    'price' => doubleval($order->pay_price - $order->express_price)
                ];
            }


            array_push($newList, $newItem);
        }
        return $newList;
    }

    private function getYyOrderDetail()
    {
        /* @var $order YyOrder */
        $order = $this->order;
        $yy_setting = YySetting::findOne(['store_id' => $order->store_id]);
        if (!$yy_setting || $yy_setting->is_share == 0) {
            return false;
        }

        $share = GoodsShare::findOne(['goods_id' => $order->goods_id, 'store_id' => $order->store_id, 'type' => GoodsShare::SHARE_GOODS_TYPE_YY]);
        $yyGoods = YyGoods::findOne(['id' => $order->goods_id, 'store_id' => $order->store_id]);

        $buyAttrList = \Yii::$app->serializer->decode($order['attr']);
        $newList = [];

        if ($share['attr_setting_type'] === 1) {
            $attrIdArr2 = [];
            foreach ($buyAttrList as $attrListItem2) {
                $attrIdArr2[] = $attrListItem2['attr_id'];
            }

            $goodsData = [
                'attr' => $yyGoods['attr'],
                'price' => $yyGoods['price'],
                'is_level' => $yyGoods['is_level'],
            ];
            $res = CommonGoods::currentGoodsAttr($goodsData, $attrIdArr2);

            $newItem = [
                'individual_share' => $share['individual_share'],
                'share_commission_first' => $res['share_commission_first'],
                'share_commission_second' => $res['share_commission_second'],
                'share_commission_third' => $res['share_commission_third'],
                'share_type' => $share['share_type'],
                'num' => 1,
                'price' => $order->pay_price
            ];

        } else {
            $newItem = [
                'individual_share' => $share['individual_share'],
                'share_commission_first' => $share['share_commission_first'],
                'share_commission_second' => $share['share_commission_second'],
                'share_commission_third' => $share['share_commission_third'],
                'share_type' => $share['share_type'],
                'num' => 1,
                'price' => $order->pay_price
            ];
        }

        array_push($newList, $newItem);

        return $newList;
    }
}
