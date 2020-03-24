<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%postage_rules}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property integer $express_id
 * @property string $detail
 * @property integer $addtime
 * @property integer $is_enable
 * @property integer $is_delete
 * @property string $express
 */
class PostageRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%postage_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'express_id', 'detail', 'type'], 'required'],
            [['store_id', 'express_id', 'addtime', 'is_enable', 'is_delete', 'type'], 'integer'],
            [['detail'], 'string'],
            [['name', 'express'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => '名称',
            'express_id' => '物流公司',
            'detail' => '规则详细',
            'addtime' => 'Addtime',
            'is_enable' => '是否启用：0=否，1=是',
            'is_delete' => 'Is Delete',
            'express' => '快递公司',
            'type' => '计费方式',
        ];
    }

    public function getExpress()
    {
        return $this->hasOne(Express::className(), ['id' => 'express_id']);
    }

    public static function getExpressPrice($store_id, $city_id, $goods, $num, $province_id)
    {
        if ($goods->freight != '0') {
            if (isset($goods->mch_id) && $goods->mch_id) {
                $postage_rules = MchPostageRules::findOne([
                    'is_delete' => 0,
                    'id' => $goods->freight,
                    'mch_id' => $goods->mch_id,
                ]);
            } else {
                $postage_rules = PostageRules::findOne([
                    'store_id' => $store_id,
                    'is_delete' => 0,
                    'id' => $goods->freight,
                ]);
            }
        } else {
            if (isset($goods->mch_id) && $goods->mch_id) {
                $postage_rules = MchPostageRules::findOne([
                    'is_delete' => 0,
                    'is_enable' => 1,
                    'mch_id' => $goods->mch_id,
                ]);
            } else {
                $postage_rules = PostageRules::findOne([
                    'store_id' => $store_id,
                    'is_delete' => 0,
                    'is_enable' => 1,
                ]);
            }
        }
        if (!$postage_rules) {
            return 0.00;
        }
        $price = 0.00;
        $list = json_decode($postage_rules->detail);
        $matching = null;
        foreach ($list as $i => $item) {
            $in_array = false;
            foreach ($item->province_list as $j => $province) {
                if ($province->id == $province_id || $province->id==$city_id) {
                    $in_array = true;
                    break;
                }
            }
            if ($in_array) {
//                $price = $item->price;
//                break;
                $matching = $list[$i];
            }
        }
        if ($matching==null) {
            return 0;
        }
        if ($postage_rules->type == 1) {
            // 按重计费
            $totalWeight = $goods->weight * $num;
            $totalWeight -= $matching->frist;
            $price += $matching->frist_price;
            $leave = $matching->second ? (ceil($totalWeight / $matching->second)>0?ceil($totalWeight / $matching->second):0) : 0;
            $price += $leave * $matching->second_price;
        } else {
            // 按件计费
            $num -= $matching->frist;
            $price += $matching->frist_price;
            $leave = $matching->second ? (ceil($num / $matching->second)>0?ceil($num / $matching->second):0) : 0;
            $price += $leave * $matching->second_price;
        }
        return $price;
    }

    /**
     * 购物车结算 多种运费规则组合计算运费
     * @param $store_id
     * @param $province_id
     * @param $goods
     * @return float|int
     */
    public static function getExpressPriceMore($store_id, $city_id, $goodsList, $province_id)
    {
        $newGoodsList = [];
        foreach ($goodsList as $row) {
            if (isset($newGoodsList[$row['freight']])) {
                $newGoodsList[$row['freight']]['num'] += $row['num'];
                $newGoodsList[$row['freight']]['weight'] += $row['weight'];
            } else {
                $newGoodsList[$row['freight']] = $row;
            }
        }
        foreach ($newGoodsList as $key => $goods) {
            if ($goods['freight'] != '0') {
                if (isset($goods['mch_id']) && $goods['mch_id']) {
                    $postage_rules = MchPostageRules::find()->andWhere([
                        'is_delete' => 0,
                        'id' => $goods['freight'],
                        'mch_id' => $goods['mch_id'],
                    ])->asArray()->one();
                } else {
                    $postage_rules = PostageRules::find()->andWhere([
                        'store_id' => $store_id,
                        'is_delete' => 0,
                        'id' => $goods['freight'],
                    ])->asArray()->one();
                }
            } else {
                if (isset($goods['mch_id']) && $goods['mch_id']) {
                    $postage_rules = MchPostageRules::find()->andWhere([
                        'is_delete' => 0,
                        'is_enable' => 1,
                        'mch_id' => $goods['mch_id'],
                    ])->asArray()->one();
                } else {
                    $postage_rules = PostageRules::find()->andWhere([
                        'store_id' => $store_id,
                        'is_delete' => 0,
                        'is_enable' => 1,
                    ])->asArray()->one();
                }
            }

            if ($postage_rules) {
                $list = json_decode($postage_rules['detail']);
                $matching = null;
                foreach ($list as $i => $item) {
                    $in_array = false;
                    foreach ($item->province_list as $j => $province) {
                        if ($province->id == $province_id || $province->id==$city_id) {
                            $in_array = true;
                            break;
                        }
                    }
                    if ($in_array) {
                        $matching = $list[$i];
                    }
                }
                $newGoodsList[$key]['type']       = $postage_rules['type'];
                $newGoodsList[$key]['matching']   = $matching;
            }
        }

        $maxFristPrice = 0;
        $maxFristPriceIndex = null;
        foreach ($newGoodsList as $k => $m) {
            if ($m['matching']->frist_price >= $maxFristPrice) {
                $maxFristPrice = $m['matching']->frist_price;
                $maxFristPriceIndex = $k;
            }
        }
        $price = 0.00;
        foreach ($newGoodsList as $key => $value) {
            if ($key == $maxFristPriceIndex) {
                if ($value['type'] == '1') {
                    // 按重计费
                    $totalWeight = $value['weight'];
                    $totalWeight -= $value['matching']->frist;
                    $price += $value['matching']->frist_price;
                    if ($value['matching']->second) {
                        $leave = ceil($totalWeight / $value['matching']->second) > 0 ? ceil($totalWeight / $value['matching']->second) : 0;
                    } else {
                        $leave = 0;
                    }
                    $price += $leave * $value['matching']->second_price;
                } else {
                    // 按件计费
                    $value['num'] -= $value['matching']->frist;
                    $price += $value['matching']->frist_price;
                    if ($value['matching']->second) {
                        $leave = ceil($value['num'] / $value['matching']->second) > 0 ? ceil($value['num'] / $value['matching']->second) : 0;
                    } else {
                        $leave = 0;
                    }
                    $price += $leave * $value['matching']->second_price;
                }
            } else {
                if ($value['type'] == '1') {
                    // 按重计费
                    $totalWeight = $value['weight'];
                    if ($value['matching']->second) {
                        $leave = ceil($totalWeight / $value['matching']->second) > 0 ? ceil($totalWeight / $value['matching']->second) : 0;
                    } else {
                        $leave = 0;
                    }
                    $price += $leave * $value['matching']->second_price;
                } else {
                    // 按件计费
                    if ($value['matching']->second) {
                        $leave = ceil($value['num'] / $value['matching']->second) > 0 ? ceil($value['num'] / $value['matching']->second) : 0;
                    } else {
                        $leave = 0;
                    }
                    $price += $leave * $value['matching']->second_price;
                }
            }
        }
        return $price;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
