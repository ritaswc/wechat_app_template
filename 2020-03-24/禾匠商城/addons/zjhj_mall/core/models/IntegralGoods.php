<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%integral_goods}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $price
 * @property string $original_price
 * @property string $detail
 * @property string $cat_id
 * @property integer $status
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $attr
 * @property integer $sort
 * @property integer $virtual_sales
 * @property string $cover_pic
 * @property string $unit
 * @property double $weight
 * @property string $freight
 * @property integer $use_attr
 * @property integer $goods_num
 * @property integer $integral
 * @property integer $service
 * @property integer $cost_price
 * @property integer $goods_pic_list
 * @property integer $is_index
 * @property integer $user_num
 */
class IntegralGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'cat_id','original_price', 'detail', 'integral','user_num'], 'required'],
            [['store_id', 'cat_id', 'status', 'addtime', 'is_delete', 'sort', 'virtual_sales', 'freight', 'use_attr', 'goods_num', 'integral','is_index'], 'integer'],
            [['price', 'original_price', 'weight','cost_price'], 'number'],
            [['detail', 'cover_pic'], 'string'],
            [['name', 'unit'], 'string', 'max' => 255],
            [['service'], 'string', 'max' => 2000],
            [['goods_pic_list','attr'], 'safe'],
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
            'name' => '商品名称',
            'price' => '商品价格',
            'original_price' => '商品原价',
            'detail' => '商品详情',
            'cat_id' => '商品分类',
            'attr' => '规格',
            'sort' => '排序',
            'virtual_sales' => '虚拟销量',
            'cover_pic' => '商品缩略图',
            'unit' => '单位',
            'weight' => '重量',
            'freight' => '运费模板',
            'use_attr' => 'Use Attr',
            'goods_num' => '商品库存',
            'integral' => '商品所需积分',
            'service' => '商品服务选项',
            'cost_price' => '成本价',
            'goods_pic_list' => '商品图片',
            'is_index' => '放置首页',
            'user_num' => '用户每日可兑换数量',
        ];
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
                    $attr_rows[$i]['price'] = $this->price;
                }
                if (!$attr_rows[$i]['integral']) {
                    $attr_rows[$i]['integral'] = $this->integral;
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
     * 获取商品销量
     */
    public function getSalesVolume()
    {
        $res = OrderDetail::find()->alias('od')
            ->select('SUM(od.num) AS sales_volume')
            ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
            ->where(['od.is_delete' => 0, 'od.goods_id' => $this->id, 'o.is_delete' => 0, 'o.is_pay' => 1,])
            ->asArray()->one();
        return empty($res['sales_volume']) ? 0 : intval($res['sales_volume']);
    }



    public static function getGoodsCard($id = null)
    {
        if (!$id) {
            return [];
        }
        //商品卡券
        $goods_card_list = GoodsCard::find()->alias('gc')->where(['gc.is_delete' => 0, 'goods_id' => $id])
            ->leftJoin(Card::tableName() . ' c', 'c.id=gc.card_id')->select([
                'gc.card_id', 'c.name', 'c.pic_url', 'gc.goods_id', 'c.content'
            ])->asArray()->all();
        foreach ($goods_card_list as $index => $value) {
            $goods_card_list[$index]['id'] = $value['card_id'];
        }
        return $goods_card_list;
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
            ->leftJoin(['a' => Attr::tableName()], 'a.attr_group_id=ag.id')
            ->where(['a.id' => $att_id])
            ->one();
        if (!$attr_group) {
            return $attr_group;
        }
        Yii::$app->cache->set($cache_key, $attr_group, 10);
        return $attr_group;
    }

    /**
     * @param \app\models\Goods $goods
     */
    public static function getCatList($goods)
    {
        $cat_list = [];
        $cat = GoodsCat::findAll(['goods_id'=>$goods->id,'store_id'=>$goods->store_id,'is_delete'=>0]);
        if ($cat) {
            foreach ($cat as $index => $value) {
                $cat_name = Cat::findOne(['id'=>$value->cat_id,'store_id'=>$goods->store_id]);
                $cat_list[$index]['cat_id'] = $value->cat_id;
                $cat_list[$index]['cat_name'] = $cat_name->name;
            }
        } else {
            $cat_name = Cat::findOne(['id'=>$goods->cat_id]);
            $cat_list[0]['cat_id'] = $goods->cat_id;
            $cat_list[0]['cat_name'] = $cat_name->name;
        }
        return $cat_list;
    }

    /**
     * @param \app\models\Goods $goods
     */
    public function getCatList1()
    {
        $cat_list = [];
        $cat = GoodsCat::findAll(['goods_id'=>$this->id,'store_id'=>$this->store_id,'is_delete'=>0]);
        if ($cat) {
            foreach ($cat as $index => $value) {
                $cat_name = Cat::findOne(['id'=>$value->cat_id,'store_id'=>$this->store_id]);
                $cat_list[] = $cat_name->name;
            }
        } else {
            $cat_name = Cat::findOne(['id'=>$this->cat_id]);
            $cat_list[] = $cat_name->name;
        }
        return implode(',', $cat_list);
    }

    public function beforeSave($insert)
    {
        if ($this->goods_num == 0) {
            $this->goods_num = $this->getNum();
        }
        return parent::beforeSave($insert);
    }

    public function getGoodsNum()
    {
        if ($this->goods_num == 0) {
            $this->goods_num = $this->getNum();
        }
        return $this->goods_num;
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
    public static function numAddStatic($goods_id, $attr_id_list, $num)
    {
        $goods = Goods::findOne($goods_id);
        if (!$goods) {
            return false;
        }
        return $goods->numAdd($attr_id_list, $num);
    }

    /**
     * 库存减少操作
     */
    public static function numSubStatic($goods_id, $attr_id_list, $num)
    {
        $goods = Goods::findOne($goods_id);
        if (!$goods) {
            return false;
        }
        return $goods->numSub($attr_id_list, $num);
    }
}
