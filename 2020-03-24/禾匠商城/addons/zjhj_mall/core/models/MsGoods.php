<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%ms_goods}}".
 *
 * @property string $id
 * @property string $name
 * @property string $original_price
 * @property string $detail
 * @property integer $status
 * @property string $service
 * @property string $sort
 * @property string $virtual_sales
 * @property string $cover_pic
 * @property string $addtime
 * @property integer $is_delete
 * @property string $sales
 * @property string $store_id
 * @property string $video_url
 * @property string $unit
 * @property double $weight
 * @property string $freight
 * @property string $full_cut
 * @property string $integral
 * @property integer $use_attr
 * @property string $attr
 * @property string $coupon;
 * @property string $is_discount
 * @property string $payment
 * @property integer $individual_share
 * @property string $share_commission_first
 * @property string $share_commission_second
 * @property string $share_commission_third
 * @property integer $share_type
 * @property string $rebate
 */
class MsGoods extends \yii\db\ActiveRecord
{
    public $is_level;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ms_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'original_price', 'detail', 'store_id', 'attr'], 'required'],
            [['original_price', 'weight', 'share_commission_first', 'share_commission_second', 'share_commission_third', 'rebate','is_level'], 'number'],
            [['detail', 'cover_pic', 'video_url', 'full_cut', 'integral', 'attr'], 'string'],
            [['status', 'sort', 'virtual_sales', 'addtime', 'is_delete', 'sales', 'store_id', 'freight', 'use_attr', 'is_discount', 'coupon', 'individual_share', 'share_type'], 'integer'],
            [['name', 'unit', 'payment'], 'string', 'max' => 255],
            [['service'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'original_price' => '原价',
            'detail' => '商品详情，图文',
            'status' => '上架状态【1=> 上架，2=> 下架】',
            'service' => '服务选项',
            'sort' => '商品排序 升序',
            'virtual_sales' => '虚拟销量',
            'cover_pic' => '商品缩略图',
            'addtime' => '添加时间',
            'is_delete' => '是否删除',
            'sales' => '实际销量',
            'store_id' => 'Store ID',
            'video_url' => '视频',
            'unit' => '单位',
            'weight' => '重量',
            'freight' => '运费模板ID',
            'full_cut' => '满减',
            'integral' => '积分设置',
            'use_attr' => '是否使用规格：0=不使用，1=使用',
            'attr' => '规格的库存及价格',
            'coupon' => '是否支持优惠劵',
            'is_discount' => '是否支持会员折扣',
            'payment' => '支付方式',
            'individual_share' => '是否单独分销设置：0=否，1=是',
            'share_commission_first' => '一级分销佣金比例',
            'share_commission_second' => '二级分销佣金比例',
            'share_commission_third' => '三级分销佣金比例',
            'share_type' => '佣金配比 0--百分比 1--固定金额',
            'rebate' => '自购返利',
        ];
    }

    public function getGoodsPicList()
    {
        return $this->hasMany(MsGoodsPic::className(), ['goods_id' => 'id'])->where(['is_delete' => 0]);
    }

    /**
     * 获取商品总库存
     * @param int $id 商品id
     */
    public function getNum($id = null)
    {
        $goods = null;
        if (!$id) {
            $goods = $this;
        } else {
            $goods = static::findOne($id);
            if (!$goods) {
                return 0;
            }
        }
        if (!$goods->attr) {
            return 0;
        }
        $num = 0;
        $attr_rows = json_decode($goods->attr, true);
        foreach ($attr_rows as $attr_row) {
            $num += intval($attr_row['num']);
        }
        return $num;
    }

    public function getAttrData()
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
        $attr_group_list = [];

        $attr_data = json_decode($this->attr, true);
        foreach ($attr_data as $i => $attr_data_item) {
            foreach ($attr_data[$i]['attr_list'] as $j => $attr_list) {
                $attr_group = $this->getAttrGroupByAttId($attr_data[$i]['attr_list'][$j]['attr_id']);
                if ($attr_group) {
                    $in_list = false;
                    foreach ($attr_group_list as $k => $exist_attr_group) {
                        if ($exist_attr_group['attr_group_name'] == $attr_group->attr_group_name) {
                            $attr_item = [
                                'attr_name' => $attr_data[$i]['attr_list'][$j]['attr_name'],
                            ];
                            if (!in_array($attr_item, $attr_group_list[$k]['attr_list'])) {
                                $attr_group_list[$k]['attr_list'][] = $attr_item;
                            }
                            $in_list = true;
                        }
                    }
                    if (!$in_list) {
                        $attr_group_list[] = [
                            'attr_group_name' => $attr_group->attr_group_name,
                            'attr_list' => [
                                [
                                    'attr_name' => $attr_data[$i]['attr_list'][$j]['attr_name'],
                                ],
                            ],
                        ];
                    }
                }
            }
        }
        return $attr_group_list;
    }

    private function getAttrGroupByAttId($att_id)
    {
        $cache_key = 'get_attr_group_by_attr_id_' . $att_id;
        $attr_group = Yii::$app->cache->get($cache_key);
        if ($attr_group) {
            return $attr_group;
        }
        //$attr_group = AttrGroup::find()->alias('ag')
        //    ->leftJoin(['a' => Attr::tableName()], 'a.attr_group_id=ag.id')
        //    ->where(['a.id' => $att_id])
        //    ->one();
        $attr_group = AttrGroup::find()->alias('ag')
            ->where(['ag.id' => Attr::find()->select('attr_group_id')->distinct()->where(['id' => $att_id])])
            ->limit(1)->one();
        if (!$attr_group) {
            return $attr_group;
        }
        Yii::$app->cache->set($cache_key, $attr_group, 10);
        return $attr_group;
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


    /**
     * 根据规格获取商品的库存及规格价格信息
     * @param array $attr_id_list 规格id列表 eg. [1,4,9]
     * @return array|null eg.
     */
    public function getAttrInfo($attr_id_list)
    {
        sort($attr_id_list);
        $attr_rows = json_decode($this->attr, true);
        if (empty($attr_rows)) {
            return null;
        }
        foreach ($attr_rows as $i => $attr_row) {
            $key = [];
            foreach ($attr_row['attr_list'] as $j => $attr) {
                $key[] = $attr['attr_id'];
            }
            sort($key);
            if (!array_diff($attr_id_list, $key)) {
                if (!$attr_rows[$i]['price']) {
                    $attr_rows[$i]['price'] = $this->original_price;
                }
                return $attr_rows[$i];
            }
        }
        return null;
    }


    /**
     * 获取商品可选的规格列表
     */
    public function getAttrGroupList()
    {
        $attr_rows = json_decode($this->attr, true);
        if (empty($attr_rows)) {
            return [];
        }
        $attr_group_list = [];
        foreach ($attr_rows as $attr_row) {
            foreach ($attr_row['attr_list'] as $i => $attr) {
                $attr_id = $attr['attr_id'];
                $attr = Attr::findOne(['id' => $attr_id, 'is_delete' => 0]);
                if (!$attr) {
                    continue;
                }
                $in_list = false;
                foreach ($attr_group_list as $j => $attr_group) {
                    if ($attr_group->attr_group_id == $attr->attr_group_id) {
                        $attr_obj = (object)[
                            'attr_id' => $attr->id,
                            'attr_name' => $attr->attr_name,
                        ];
                        if (!in_array($attr_obj, $attr_group_list[$j]->attr_list)) {
                            $attr_group_list[$j]->attr_list[] = $attr_obj;
                        }
                        $in_list = true;
                        continue;
                    }
                }
                if (!$in_list) {
                    $attr_group = AttrGroup::findOne(['is_delete' => 0, 'id' => $attr->attr_group_id]);
                    if ($attr_group) {
                        $attr_group_list[] = (object)[
                            'attr_group_id' => $attr_group->id,
                            'attr_group_name' => $attr_group->attr_group_name,
                            'attr_list' => [
                                (object)[
                                    'attr_id' => $attr->id,
                                    'attr_name' => $attr->attr_name,
                                ],
                            ],
                        ];
                    }
                }
            }
        }
        return $attr_group_list;
    }

    /**
     * 库存减少操作
     * @param array $attr_id_list eg. [1,4,2]
     */
    public function numSub($attr_id_list, $num)
    {
        sort($attr_id_list);
        $attr_group_list = json_decode($this->attr);
        $sub_attr_num = false;
        foreach ($attr_group_list as $i => $attr_group) {
            $group_attr_id_list = [];
            foreach ($attr_group->attr_list as $attr) {
                array_push($group_attr_id_list, $attr->attr_id);
            }
            sort($group_attr_id_list);
            if (!array_diff($attr_id_list, $group_attr_id_list)) {
                if ($num > intval($attr_group_list[$i]->num)) {
                    return false;
                }
                $attr_group_list[$i]->num = intval($attr_group_list[$i]->num) - $num;
                $sub_attr_num = true;
                break;
            }
        }
        if (!$sub_attr_num) {
            return false;
        }
        $this->attr = json_encode($attr_group_list, JSON_UNESCAPED_UNICODE);
        $this->save();
        return true;
    }

    /**
     * 库存增加操作
     */
    public function numAdd($attr_id_list, $num)
    {
        sort($attr_id_list);
        $attr_group_list = json_decode($this->attr);
        $add_attr_num = false;
        foreach ($attr_group_list as $i => $attr_group) {
            $group_attr_id_list = [];
            foreach ($attr_group->attr_list as $attr) {
                array_push($group_attr_id_list, $attr->attr_id);
            }
            sort($group_attr_id_list);
            if (!array_diff($attr_id_list, $group_attr_id_list)) {
                $attr_group_list[$i]->num = intval($attr_group_list[$i]->num) + $num;
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

    /**
     * 获取商品销量
     */
    public function getSalesVolume()
    {
        $res = MsOrder::find()->select('SUM(num) as sales_volume')
            ->where(['is_delete'=>0,'goods_id'=>$this->id,'is_pay'=>1])->asArray()->one();
        return empty($res['sales_volume']) ? 0 : intval($res['sales_volume']);
    }

    // 获取默认规格商品的货号
    public function getGoodsNo($id = null)
    {
        $goods = null;
        if (!$id) {
            $goods = $this;
        } else {
            $goods = static::findOne($id);
            if (!$goods) {
                return 0;
            }
        }
        if (!$goods->attr) {
            return 0;
        }
        $num = 0;
        $attr_rows = json_decode($goods->attr, true);
        foreach ($attr_rows as $attr_row) {
            $num = $attr_row['no'];
        }
        return $num;
    }

    public function getIsLevel() {
        return $this->is_level = $this->is_discount;
    }


    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
