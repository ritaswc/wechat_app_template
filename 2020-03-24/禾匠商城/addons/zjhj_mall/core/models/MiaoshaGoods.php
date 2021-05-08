<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%miaosha_goods}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property integer $start_time
 * @property string $open_date
 * @property string $attr
 * @property integer $is_delete
 * @property integer $buy_max
 * @property string $buy_limit
 * @property string $is_level;
 */
class MiaoshaGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%miaosha_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'start_time', 'open_date', 'attr'], 'required'],
            [['store_id', 'goods_id', 'start_time', 'is_delete', 'buy_max', 'buy_limit', 'is_level'], 'integer'],
            [['open_date'], 'safe'],
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
            'start_time' => '开始时间：0~23',
            'open_date' => '开放日期，例：2017-08-21',
            'attr' => '规格秒杀价数量',
            'is_delete' => 'Is Delete',
            'buy_max' => '限购数量，0=不限购',
            'buy_limit' => '限单',
            'is_level' => '会员折扣',
        ];
    }

    //库存增加操作
    public function numAdd($attr_id_list, $num)
    {
        sort($attr_id_list);
        $attr_group_list = json_decode($this->attr, true);
        $add_attr_num = false;
        foreach ($attr_group_list as $i => $attr_group) {
            $group_attr_id_list = [];
            foreach ($attr_group['attr_list'] as $attr) {
                array_push($group_attr_id_list, $attr['attr_id']);
            }
            sort($group_attr_id_list);
            if (!array_diff($attr_id_list, $group_attr_id_list)) {
                $attr_group_list[$i]['sell_num'] = intval($attr_group_list[$i]['sell_num']) - $num;
                $add_attr_num = true;
                break;
            }
        }
        if (!$add_attr_num) {
            return false;
        }
        $this->attr = json_encode($attr_group_list, JSON_UNESCAPED_UNICODE);
        $this->save();
        return true;
    }

    //判断秒杀订单的有效性
    public function is_valid($goods_info, $user_id)
    {
        $attr_id_list = [];
        foreach ($goods_info->attr as $item) {
            array_push($attr_id_list, $item->attr_id);
        }
        $attr_group_list = json_decode($this->attr, true);
        if ($goods_info->num > $this->buy_max && $this->buy_max > 0) {
            return [
                'code'=>1,
                'msg'=>'当前活动每单限购'.$this->buy_max
            ];
        }
        foreach ($attr_group_list as $attr_group) {
            $goods_attr_id_list = [];
            foreach ($attr_group as $attr) {
                array_push($goods_attr_id_list, $attr['attr_id']);
            }
            if (!array_diff($attr_id_list, $goods_attr_id_list)) {
                if ($attr_group['miaosha_num'] - $attr_group['sell_num'] < $goods_info->num) {
                    return [
                        'code'=>1,
                        'msg'=>'当前规格商品已售罄'
                    ];
                }
            }
        }
        $order_count = MsOrder::find()->where([
            'store_id'=>$this->store_id,'user_id'=>$user_id,'is_cancel'=>0,
            'is_delete'=>0,'goods_id'=>$this->goods_id
        ])->andWhere([
                'between',
                'addtime',
                strtotime($this->open_date.' '.$this->start_time.':00:00'),
                strtotime($this->open_date.' '.$this->start_time.':59:59')
            ])->count();
        if ($order_count >= $this->buy_limit && $this->buy_limit > 0) {
            return [
                'code'=>1,
                'msg'=>'当前活动限购'.$this->buy_limit.'单'
            ];
        }
        return [
            'code'=>0,
            'msg'=>'success'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }

    public function getMsGoods()
    {
        return $this->hasOne(MsGoods::className(),['id'=>'goods_id']);
    }
}
