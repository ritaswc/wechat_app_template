<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pt_goods_detail}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property string $colonel
 * @property string $group_num
 * @property integer $group_time
 * @property string $attr
 * @property int $is_level
 */
class PtGoodsDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_goods_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'goods_id', 'group_num', 'group_time'], 'integer'],
            [['colonel'], 'number'],
            [['attr'], 'string'],
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
            'goods_id' => 'Goods ID',
            'colonel' => '团长优惠',
            'group_num' => '商品成团数',
            'group_time' => '拼团时间/小时',
            'attr' => '规格的库存及价格',
            'is_level' => '是否享受会员折扣 0-不享受 1--享受',
        ];
    }

    public function getCheckedAttrData()
    {
        if ($this->isNewRecord) {
            return [];
        }
        if (!$this->use_attr) {
            return [];
        }
        if (!$this->attr) {
            return [];
        }
        $attr_data = json_decode($this->attr, true);
        foreach ($attr_data as $i => $attr_data_item) {
            if (!isset($attr_data[$i]['no'])) {
                $attr_data[$i]['no'] = '';
            }
            if (!isset($attr_data[$i]['pic'])) {
                $attr_data[$i]['pic'] = '';
            }
            foreach ($attr_data[$i]['attr_list'] as $j => $attr_list) {
                $attr_group = $this->getAttrGroupByAttId($attr_data[$i]['attr_list'][$j]['attr_id']);
                $attr_data[$i]['attr_list'][$j]['attr_group_name'] = $attr_group ? $attr_group->attr_group_name : null;
            }
        }
        return $attr_data;
    }

    private function getAttrGroupByAttId($att_id)
    {
        $cache_key = 'get_attr_group_by_attr_id_' . $att_id;
        $attr_group = Yii::$app->cache->get($cache_key);
        if ($attr_group) {
            return $attr_group;
        }

        $attr_group = AttrGroup::find()->alias('ag')
            ->where(['ag.id' => Attr::find()->select('attr_group_id')->distinct()->where(['id' => $att_id])])
            ->limit(1)->one();
        if (!$attr_group) {
            return $attr_group;
        }
        Yii::$app->cache->set($cache_key, $attr_group, 10);
        return $attr_group;
    }
}
