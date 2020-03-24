<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_share}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property integer $goods_id
 * @property integer $individual_share
 * @property string $share_commission_first
 * @property string $share_commission_second
 * @property string $share_commission_third
 * @property integer $share_type
 * @property string $rebate
 * @property string $attr_setting_type
 * @property integer relation_id
 */
class GoodsShare extends \yii\db\ActiveRecord
{
    public $is_level;
    /**
     * 分销商品类型：拼团
     */
    const SHARE_GOODS_TYPE_PT = 0;

    /**
     * 分销商品类型：预约
     */
    const SHARE_GOODS_TYPE_YY = 1;

    /**
     * 分销商品类型：秒杀商品设置
     */
    const SHARE_GOODS_TYPE_MS = 2;

    /**
     * 分销商品类型：拼团阶梯团
     */
    const SHARE_GOODS_TYPE_PT_STANDARD = 3;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'type', 'goods_id', 'individual_share', 'share_type', 'relation_id'], 'integer'],
            [['share_commission_first', 'share_commission_second', 'share_commission_third', 'rebate', 'attr_setting_type'], 'number'],
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
            'type' => '商品类型 0--拼团',
            'goods_id' => '商品id',
            'individual_share' => '是否单独分销设置：0=否，1=是',
            'share_commission_first' => '一级分销佣金比例',
            'share_commission_second' => '二级分销佣金比例',
            'share_commission_third' => '三级分销佣金比例',
            'share_type' => '佣金配比 0--百分比 1--固定金额',
            'rebate' => '自购返利',
            'relation_id' => '判断秒杀活动ID',
        ];
    }
}
