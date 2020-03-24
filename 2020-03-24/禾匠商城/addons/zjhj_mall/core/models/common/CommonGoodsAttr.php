<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common;


use app\models\Attr;
use app\models\AttrGroup;
use app\models\Goods;
use app\models\Model;
use app\models\PtGoods;
use app\models\PtGoodsDetail;
use yii\db\mssql\PDO;
use yii\db\Query;

class CommonGoodsAttr
{

    /**
     * 给规格列表添加个 规格组名称字段(商品编辑时需要用到)
     * @param array $goods
     * @return array
     */
    public static function getCheckedAttr($goods)
    {
        $storeId = \Yii::$app->controller->store->id;

        $attr = is_string($goods['attr']) ? \Yii::$app->serializer->decode($goods['attr']) : $goods['attr'];

        $newAttr = [];
        foreach ($attr as $k => $item) {
            $newAttr[$k] = $item;
        }

        foreach ($newAttr as $k => $item) {
            foreach ($item['attr_list'] as $k2 => $item2) {
                $attr = Attr::find()->where(['id' => $item2['attr_id'], 'is_delete' => Model::IS_DELETE_FALSE])->asArray()->one();
                $cache_key = 'attr_group_by_attr_' . $attr['attr_group_id'];
                $attrGroup = \Yii::$app->cache->get($cache_key);

                if (!$attrGroup) {
                    $attrGroup = AttrGroup::find()->where(['id' => $attr['attr_group_id'], 'store_id' => $storeId])->asArray()->one();
                    \Yii::$app->cache->set($cache_key, $attrGroup, 1);

                }

                $newAttr[$k]['attr_list'][$k2]['attr_group_name'] = $attrGroup['attr_group_name'];
            }
        }

        return $newAttr;
    }


    /**
     * 商品库存减少
     * @param $attrIdList
     * @param $num
     * @param array $otherData
     * @return array
     */
    public static function num($attrIdList, $num, $otherData = [])
    {
        // TODO 事务暂时先关闭
        // $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!isset($otherData['action_type']) || !in_array($otherData['action_type'], ['add', 'sub'])) {
                return [
                    'code' => 1,
                    'msg' => '请传入操作类型action_type, 必须[add||sub]'
                ];
            }

            $goodId = $otherData['good_id'];
            switch ($otherData['good_type']) {
                case 'STORE':
                    $good = Goods::findOne($goodId);
                    break;
                case 'PINTUAN':
                    $good = PtGoods::findOne($goodId);
                    break;
                default:
                    return [
                        'code' => 1,
                        'msg' => '无此商品类型'
                    ];
                    break;
            }

            sort($attrIdList);
            $attrs = json_decode($good->attr);
            $subAttrNum = false;

            foreach ($attrs as $i => $attr) {
                $goodAttrIdList = [];
                foreach ($attr->attr_list as $item) {
                    array_push($goodAttrIdList, $item->attr_id);
                }
                sort($goodAttrIdList);
                if (!array_diff($attrIdList, $goodAttrIdList)) {
                    if ($num > intval($attrs[$i]->num)) {
                        return [
                            'code' => 1,
                            'msg' => '商品库存不足,无法购买',
                        ];
                    }

                    if ($otherData['action_type'] === 'add') {
                        $attrs[$i]->num = intval($attrs[$i]->num) + $num;
                    }

                    if ($otherData['action_type'] === 'sub') {
                        $attrs[$i]->num = intval($attrs[$i]->num) - $num;
                    }

                    $subAttrNum = true;
                    break;
                }
            }
            if (!$subAttrNum) {
                return [
                    'code' => 1,
                    'msg' => '商品规格信息有误,请检查商品'
                ];
            }
            $good->attr = json_encode($attrs, JSON_UNESCAPED_UNICODE);

            $isTrue = true;
            // 阶梯团规格库存 与拼团商品需一致
            if ($otherData['good_type'] === 'PINTUAN') {
                $ptGoodsDetail = PtGoodsDetail::find()->where(['goods_id' => $otherData['good_id']])->one();
                $ptGoodAttrs = json_decode($ptGoodsDetail->attr, true);

                if ($ptGoodAttrs) {
                    foreach ($ptGoodAttrs as $k => &$item) {
                        $item['num'] = $attrs[$k]->num;
                    }

                    $ptGoodsDetail->attr = json_encode($ptGoodAttrs);

                    if (!$ptGoodsDetail->save()) {
                        $isTrue = false;
                    }
                }
            }

            if (!$good->save()) {
                $isTrue = false;
            }

            if ($isTrue) {
                // $transaction->commit();
                return [
                    'code' => 0,
                    'msg' => '库存充足，可以购买'
                ];
            }

            return [
                'code' => 1,
                'msg' => '服务器出错啦！'
            ];

        } catch (\Exception $e) {
            // $transaction->rollBack();

            return [
                'code' => 1,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
