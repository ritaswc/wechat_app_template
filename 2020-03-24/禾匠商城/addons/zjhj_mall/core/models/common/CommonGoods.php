<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common;


use app\models\Level;
use app\models\MchPlugin;
use app\models\MchSetting;
use app\models\Model;
use app\models\MsSetting;
use app\models\PtSetting;
use app\models\Setting;
use app\models\YySetting;

class CommonGoods
{
    /**
     * 获取当前规格的相应信息
     * @param array $goods 商品信息
     * @param array $currentAttrIds 当前选择的规格数据 例:[42,43]
     * @param array $otherData 特殊数据处理 阶梯团、秒杀
     * @return array
     */
    public static function currentGoodsAttr(array $goods, array $currentAttrIds, array $otherData = [])
    {
        $attrs = \Yii::$app->serializer->decode($goods['attr']);
        $level = self::currentLevelDiscount();


        foreach ($attrs as $attr) {
            $attrIds = [];
            foreach ($attr['attr_list'] as $item) {
                $attrIds[] = $item['attr_id'];
            }

            sort($attrIds);
            sort($currentAttrIds);

            // 找出当前规格信息
            if (implode($attrIds) === implode($currentAttrIds)) {

                // price 前端展示价格(该价格可以是 原价、会员价)
                $data['price'] = $attr['price'] > 0 ? $attr['price'] : $goods['price'];
                $data['num'] = $attr['num'];
                $data['goods_price'] = $attr['price'] > 0 ? $attr['price'] : $goods['price'];//商品售价
                $data['attr_list'] = $attr['attr_list'];
                $data['pic'] = $attr['pic'];
                $data['no'] = $attr['no'];
                $data['share_commission_first'] = $attr['share_commission_first'];
                $data['share_commission_second'] = $attr['share_commission_second'];
                $data['share_commission_third'] = $attr['share_commission_third'];
                $data['is_member_price'] = false;
                $data['is_level'] = false;
                $data['level_price'] = $attr['price'] > 0 ? $attr['price'] : $goods['price'];//会员折扣价


                // 秒杀表价格字段
                if ($otherData['type'] === 'MIAOSHA') {
                    $data['price'] = $attr['miaosha_price'] > 0 ? $attr['miaosha_price'] : $goods['price'];
                    $data['level_price'] = $attr['miaosha_price'] > 0 ? $attr['miaosha_price'] : $goods['price'];
                    $data['num'] = $attr['miaosha_num'] - $attr['sell_num'];
                    $data['miaosha_num'] = $attr['miaosha_num'] - $attr['sell_num'];
                    $data['sell_num'] = $attr['sell_num'];
                    $data['id'] = $otherData['id'];
                    $data['miaosha_num_count'] = $attr['miaosha_num'];
                }

                // 拼团有单买价
                if ($otherData['type'] === 'PINTUAN') {
                    $data['single_price'] = sprintf('%.2f', $attr['single']) > 0 ? sprintf('%.2f', $attr['single']) : sprintf('%.2f', $otherData['single_price']);
                    $data['single'] = $data['single_price'];
                }

                // 用户等级 可以传入指定用户等级
                $userLevel = $otherData['user_level'] ? $otherData['user_level'] : $level['userLevel'];
                $keyName = 'member' . $userLevel;
                // 为会员用户 会员价 > 0 并且商品开启了会员折扣 则显示会员价
                if ($attr[$keyName] > 0 && $goods['is_level'] && $level['userLevel'] >= 0) {
                    $data['price'] = $attr[$keyName];
                    $data['is_member_price'] = true;
                    $data['is_level'] = true;
                }

                // 为会员用户 开启了规格会员价、但没设置或者规格价等于0 则使用全局会员折扣
                if ($attr[$keyName] <= 0 && $goods['is_level'] && $level['userLevel'] >= 0) {
                    // 如果算出的会员折扣价 < 0.01 那会员价就是0
                    $data['price'] = (($data['price'] * $level['discount']) / 10) >= 0.01 ? (($data['price'] * $level['discount']) / 10) : 0.00;
                    $data['is_member_price'] = true;
                    $data['is_level'] = true;
                }


                // TODO 特殊 之前代码有用到该字段 用于秒杀插件
                if ($otherData['type'] === 'MIAOSHA') {
                    $data['miaosha_price'] = $data['price'];
                    $data['goods_price'] = $attr['miaosha_price'] > 0 ? $attr['miaosha_price'] : $goods['price'];//商品售价
                }

                // 商城开启了会员折扣 且是会员用户
                if ($level['userLevel'] >= 0 && $goods['is_level']) {

                    // 会员折扣价为 会员价
                    if ($data['is_member_price'] === true) {
                        $data['level_price'] = $data['price'];
//                        $data['is_level'] = false;
                    } else {
                        // 会员折扣价 根据会员折扣计算而来
                        $data['level_price'] = ($data['price'] * floatval($level['discount']) / 10) >= 0.01 ? ($data['price'] * floatval($level['discount']) / 10) : 0.00;
                        $data['is_level'] = true;
                    }

                    // TODO 特殊 拼团单买暂时不需要计算会员折扣
                    if ($otherData['order_type'] === 'ONLY_BUY') {
                        $data['is_level'] = false;
                        // TODO 以下两个字段会被覆盖，没什么作用
                        $data['goods_price'] = $attr['single'] > 0 ? $attr['single'] : $otherData['single_price'];
                        $data['level_price'] = $data['single_price'];
                    }

                }

                // 商城开启了会员折扣 且是普通用户
                if ($level['userLevel'] === -1 && $goods['is_level']) {

                    // 会员折扣价为 会员价
                    if ($data['is_member_price'] === true) {
                        $data['level_price'] = $data['goods_price'];
                        $data['is_level'] = false;
                    } else {
                        // 会员折扣价 根据会员折扣计算而来
                        $data['level_price'] = $data['goods_price'];
                        $data['is_level'] = false;
                    }

                    if ($otherData['order_type'] === 'ONLY_BUY') {
                        $data['is_level'] = false;
                        $data['goods_price'] = $attr['single'] > 0 ? $attr['single'] : $otherData['single_price'];
                        $data['level_price'] = $data['single_price'];
                    }
                }

                // 是否为多商户商品 多商户商品没有会员价
                if (isset($goods['mch_id']) && $goods['mch_id'] > 0) {
                    $data['level_price'] = $attr['price'] > 0 ? $attr['price'] : $goods['price'];
                    $data['price'] = $attr['price'] > 0 ? $attr['price'] : $goods['price'];
                    $data['is_member_price'] = false;
                    $data['is_level'] = false;
                }

                $data['price'] = sprintf("%.2f", $data['price']) > 0 ? sprintf("%.2f", $data['price']) : $goods['price'];

                return $data;
            }
        }
    }

    /**
     * 获取当前商品的最高分销价、及最低会员价(根据用户等级)
     * user_is_member 是否会员
     * is_level 是否开启会员折扣
     * is_share 是否开启分销
     * min_member_price 最低会员价
     * max_share_price 最高分销价
     * @param $goods
     * @return array
     */
    public static function getMMPrice(array $goods, $otherData = [])
    {
        $attrs = \Yii::$app->serializer->decode($goods['attr']);
        $storeId = \Yii::$app->store->id;
        $level = self::currentLevelDiscount();
        // 商城全局分销设置
        $shareSetting = Setting::findOne(['store_id' => $storeId]);


        $maxSharePriceArr = [];
        $minMemberPriceArr = [];

        foreach ($attrs as $attr) {
            $price = $attr['price'] > 0 ? $attr['price'] : $goods['price'];

            // 秒杀插件规格价字段有所不同
            if ($otherData['type'] === 'MIAOSHA') {
                $price = $attr['miaosha_price'] > 0 ? $attr['miaosha_price'] : $goods['price'];
            }

            // 商品开启单独分销设置 (按一级分销佣金计算)
            if ((int)$goods['individual_share'] === 1) {

                // 普通设置 (单商品全局)
                if ((int)$goods['attr_setting_type'] === 0 && $goods['share_commission_first'] > 0) {

                    // 分销普通设置 按百分比
                    if ((int)$goods['share_type'] === 0) {
                        $maxSharePriceArr[] = ($goods['share_commission_first'] * $price) / 100;

                    }

                    // 分销普通设置 按固定金额
                    if ((int)$goods['share_type'] === 1) {
                        $maxSharePriceArr[] = $goods['share_commission_first'];
                    }

                }

                // 详细设置 (多规格分销价)
                if ((int)$goods['attr_setting_type'] === 1 && $attr['share_commission_first'] > 0) {

                    if ((int)$goods['share_type'] === 0) {
                        $maxSharePriceArr[] = ($attr['share_commission_first'] * $price) / 100;
                    }

                    if ((int)$goods['share_type'] === 1) {
                        $maxSharePriceArr[] = $attr['share_commission_first'];
                    }
                }

                // 如果开启规格了 普通设置 或 详细设置，但是未设置分销价(或者为0) 则使用全局分销佣金计算
//                if (((int)$goods['attr_setting_type'] === 0 && $goods['share_commission_first'] <= 0) ||
//                    ((int)$goods['attr_setting_type'] === 1 && $attr['share_commission_first'] <= 0)) {
//
//                    if ((int)$shareSetting['price_type'] === 0) {
//                        $maxSharePriceArr[] = ($shareSetting['first'] * $price) / 100;
//                    }
//
//                    if ((int)$shareSetting['price_type'] === 1) {
//                        $maxSharePriceArr[] = $shareSetting['first'];
//                    }
//                }

            }

            // 商品未开启单独分销设置时 (按全局一级分销佣金计算)
            if ((int)$goods['individual_share'] === 0 && !(isset($goods['mch_id']) && $goods['mch_id'] != 0)) {
                // 全局分销佣金 按百分比计算
                if ((int)$shareSetting['price_type'] === 0 && $shareSetting['first'] > 0) {
                    $maxSharePriceArr[] = ($shareSetting['first'] * $price) / 100;
                }

                // 全局分销佣金 按固定金额计算
                if ((int)$shareSetting['price_type'] === 1) {
                    $maxSharePriceArr[] = $shareSetting['first'];
                }
            }

            // 开启商品会员折扣
            $data['user_is_member'] = false;
            if ((int)$goods['is_level'] === 1) {

                // 普通用户 (显示下一级会员价)
                if ($level['userLevel'] === -1) {
                    $data['user_is_member'] = false;
                    $keyName = 'member' . $level['list'][0]['level'];
                    // 多规格会员价大于 > 0 则直接展示
                    if ($attr[$keyName] > 0) {
                        $minMemberPriceArr[] = $attr[$keyName];
                    }

                    // 如果开启了会员折扣，但i设置的多规格会员价为0，则使用下一级全局会员折扣
                    if ($attr[$keyName] <= 0) {
                        $minMemberPriceArr[] = ($price * $level['list'][0]['discount']) / 10;
                    }
                }

                // 会员用户 (显示当前会员价)
                if ($level['userLevel'] >= 0) {
                    $data['user_is_member'] = true;
                    $keyName = 'member' . $level['userLevel'];

                    // 多规格会员价大于 > 0 则直接展示
                    if ($attr[$keyName] > 0) {
                        $minMemberPriceArr[] = $attr[$keyName];
                    }

                    // 如果开启了会员折扣，但设置的多规格会员价为0，则使用全局会员折扣
                    if ($attr[$keyName] <= 0) {
                        $minMemberPriceArr[] = ($price * $level['discount']) / 10;
                    }
                }


                // 多商户不计算会员价
                $data['is_mch_goods'] = false;
                if (isset($goods['mch_id']) && (int)$goods['mch_id'] > 0) {
                    $data['user_is_member'] = false;
                    $data['is_mch_goods'] = true;
                    $minMemberPriceArr[] = 0;
                }
            }

            // 未开启商品会员折扣
            if ((int)$goods['is_level'] === 0) {
                // 显示最小规格价
                $minMemberPriceArr[] = $price;
            }
        }

        $data['max_share_price'] = !empty($maxSharePriceArr) ? max($maxSharePriceArr) : 0;
        $data['min_member_price'] = !empty($minMemberPriceArr) ? min($minMemberPriceArr) : 0;


        // 商品是否开启会员折扣
        $data['is_level'] = false;
        if ((int)$goods['is_level'] === 1) {
            $data['is_level'] = true;
        }

        // 总商城分销 是否开启
        $data['is_share'] = false;
        if ($shareSetting['level'] > 0) {
            $data['is_share'] = true;
        }

        // 插件全局分销是否开启
        if (isset($otherData['type'])) {
            if ($otherData['type'] === 'MIAOSHA') {
                $query = MsSetting::find();
            }

            if ($otherData['type'] === 'PINTUAN') {
                $query = PtSetting::find();
            }

            if ($otherData['type'] === 'BOOK') {
                $query = YySetting::find();
            }

            $setting = $query->where(['store_id' => $storeId])->one();
            $pluginIsShare = $setting->is_share;

            // 插件全局分销关闭则分销价不显示
            if ((int)$pluginIsShare === 0) {
                $data['is_share'] = false;
            }

        }

        // 如果会员价 <= 0 则不显示
        if (sprintf('%.2f', $data['min_member_price']) <= 0) {
            $data['is_level'] = false;
        }

        // 如果会员中心一个会员都没有，则不显示会员价
        if (empty($level['list'])) {
            $data['is_level'] = false;
        }

        // 是否为多商户商品
        if (isset($goods['mch_id']) && $goods['mch_id'] > 0) {
            $mchPlugin = MchPlugin::findOne(['mch_id' => $goods['mch_id']]);
            $mchSetting = MchSetting::findOne(['mch_id' => $goods['mch_id']]);

            // 是否授权多商户分销
            if ((int)$mchPlugin['is_share'] === 0) {
                $data['is_share'] = false;
            }

            // 多商户自身是否开启分销
            if ((int)$mchSetting['is_share'] === 0) {
                $data['is_share'] = false;
            }
        }

        return $data;
    }

    /**
     * 获取当前会员折扣
     */
    private static function currentLevelDiscount()
    {
        $userLevel = \Yii::$app->user->identity->level;
        $storeId = \Yii::$app->store->id;

        $levelList = Level::find()->where(['store_id' => $storeId, 'is_delete' => Model::IS_DELETE_FALSE, 'status' => Level::STATUS_TRUE])
            ->select('id, level, name, discount')
            ->orderBy('level')
            ->asArray()->all();

        $currentLevelDiscount = 10;
        foreach ($levelList as $level) {
            if ((int)$level['level'] === $userLevel) {
                $currentLevelDiscount = $level['discount'];
                break;
            }
        }

        return [
            'discount' => $currentLevelDiscount,
            'userLevel' => $userLevel,
            'list' => $levelList
        ];
    }

}
