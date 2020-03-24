<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pt_goods}}".
 *
 * @property string $id
 * @property integer $store_id
 * @property string $name
 * @property string $original_price
 * @property string $price
 * @property string $detail
 * @property string $cat_id
 * @property integer $status
 * @property integer $grouptime
 * @property string $attr
 * @property string $service
 * @property string $sort
 * @property string $virtual_sales
 * @property string $cover_pic
 * @property string $weight
 * @property string $freight
 * @property string $unit
 * @property string $addtime
 * @property integer $is_delete
 * @property string $group_num
 * @property string $is_hot
 * @property string $limit_time
 * @property string $is_only
 * @property string $is_more
 * @property string $colonel
 * @property string $buy_limit
 * @property string $type
 * @property string $use_attr
 * @property string $one_buy_limit
 * @property string $payment
 * @property int $is_level
 * @property string $video_url
 */
class PtGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'original_price', 'price', 'detail', 'attr'], 'required'],
            [['store_id', 'cat_id', 'status', 'grouptime', 'sort', 'virtual_sales', 'weight', 'freight', 'addtime', 'is_delete', 'group_num', 'is_hot', 'limit_time', 'is_only', 'is_more', 'buy_limit', 'type', 'use_attr', 'one_buy_limit'], 'integer'],
            [['original_price', 'price', 'colonel'], 'number'],
            [['detail', 'attr', 'cover_pic'], 'string'],
            [['name', 'unit', 'payment', 'video_url'], 'string', 'max' => 255],
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
            'store_id' => 'Store ID',
            'name' => '商品名称',
            'original_price' => '商品原价',
            'price' => '团购价',
            'detail' => '商品详情，图文',
            'cat_id' => '商品分类',
            'status' => '上架状态【1=> 上架，2=> 下架】',
            'grouptime' => '拼团时间/小时',
            'attr' => '规格的库存及价格',
            'service' => '服务选项',
            'sort' => '商品排序 升序',
            'virtual_sales' => '虚拟销量',
            'cover_pic' => '商品缩略图',
            'weight' => '重量',
            'freight' => '运费模板ID',
            'unit' => '单位',
            'addtime' => '添加时间',
            'is_delete' => '是否删除',
            'group_num' => '商品成团数',
            'is_hot' => '是否热卖【0=>热卖1=>不是】',
            'limit_time' => '拼团限时',
            'is_only' => '是否允许单独购买',
            'is_more' => '是否允许多件购买',
            'colonel' => '团长优惠',
            'buy_limit' => '限购数量',
            'type' => '商品类型【1=> 送货上门，2=> 到店自提，3=> 全部支持】',
            'use_attr' => '是否使用规格：0=不使用，1=使用',
            'one_buy_limit' => '商品单次购买数量',
            'payment' => '支付方式',
            'is_level' => '是否享受会员折扣 0-不享受 1--享受',
            'video_url' => '商品视频',
        ];
    }

    /**
     * 阶梯团详情
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasMany(PtGoodsDetail::className(), ['goods_id' => 'id']);
    }


    /**
     * @return static[]
     * 获取商品图集
     */
    public function goodsPicList()
    {
        return PtGoodsPic::findAll(['goods_id' => $this->id, 'is_delete' => 0]);
    }

    public function getCat(){
        return $this->hasOne(PtCat::className(), ['id' => 'cat_id']);
    }

    /**
     * 获取商品总库存
     * @param array $attr 商品规格数组
     * @return int
     */
    public static function getNum($attr = [])
    {
        if (empty($attr)) {
            return 0;
        }

        $num = 0;
        foreach ($attr as $item) {
            $num += intval($item['num']);
        }

        return $num;
    }

    /**
     * 获取商品总库存 根据ID 查询
     * @param null $id
     * @return int
     */
    public function getDNum($id = null)
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
     * 商品默认规格
     */
    public function getAttrGroupListnum()
    {

        $goodsdetail = PtGoodsDetail::find()->where(['store_id' => $this->store_id])->andWhere('goods_id=:goods_id', [':goods_id' => $this->id])->all();
        $goods = (object)null;
        $goods->attr_group_name = '拼团人数';
        $goods->attr_group_id = $this->id;

        foreach ($goodsdetail as $k => $v) {
            $goods->attr_list[$k] = [
                'id' => $v->id,
                'group_num' => $v->group_num,
                'attr' => $v->attr,
                'group_time' => $v->group_time,
                'colonel' => $v->colonel,
            ];
        }
        return $goods;
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
     * 根据规格获取商品的库存及规格价格信息
     * @param array $attr_id_list 规格id列表 eg. [1,4,9]
     * @return array|null eg.
     */
    public function getAttrInfo($attr_id_list, $id = null)
    {
        sort($attr_id_list);
        $attr_rows = json_decode($this->attr, true);
        $attr = $this->info($attr_rows, $attr_id_list);
        if (!empty($attr)) {
            if (!$attr['price']) {
                $attr['price'] = $this->price;
            }
            if (!$attr['single']) {
                $attr['single'] = $this->original_price;
            }
        }

        if ($id) {
            $list = PtGoodsDetail::find()->where(['store_id' => $this->store_id])->andWhere('id=:id', [':id' => $id])->one();
            $attr_rows = json_decode($list->attr, true);

            $new = $this->info($attr_rows, $attr_id_list);

            if ($new['price']) {
                $attr['price'] = sprintf("%.2f", $new['price']);
            }
        }

        return $attr;
    }

    /*

     */
    private function info($attr_rows, $attr_id_list)
    {
        foreach ($attr_rows as $i => $attr_row) {
            $key = [];
            foreach ($attr_row['attr_list'] as $j => $attr) {
                $key[] = $attr['attr_id'];
            }
            sort($key);
            if (!array_diff($attr_id_list, $key)) {
                return $attr_rows[$i];
            }
        }
        return null;
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
     * 获取商品销量
     */
    public function getSalesVolume()
    {
        $res = PtOrderDetail::find()->alias('od')
            ->select('SUM(od.num) AS sales_volume')
            ->leftJoin(['o' => PtOrder::tableName()], 'od.order_id=o.id')
            ->where(['od.is_delete' => 0, 'od.goods_id' => $this->id, 'o.is_delete' => 0, 'o.is_pay' => 1,])
            ->asArray()->one();
        return empty($res['sales_volume']) ? 0 : intval($res['sales_volume']);
    }

    /**
     * 验证限时拼团是否超时
     */
    public function checkLimitTime($id = null)
    {
        $goods = null;
        if (!$id) {
            $goods = $this;
        } else {
            $goods = static::findOne($id);
        }
        if (!$goods) {
            return false;
        }
        if (!empty($goods->limit_time) && $goods->limit_time < time()) {
            return false;
        } else {
            return true;
        }
    }

    public static function getGoodsPicStatic($goods_id, $index = 0)
    {
        $goods = PtGoods::findOne($goods_id);
        if (!$goods) {
            return null;
        }
        return $goods->cover_pic;
    }

//    public function getGoodsPic($index = 0)
//    {
//        $list = $this->goodsPicList;
//        if (!$list)
//            return null;
//        return isset($list[$index]) ? $list[$index] : null;
//    }

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

    public function getShare()
    {
        return $this->hasOne(GoodsShare::className(), ['goods_id' => 'id'])->where(['type' => 0, 'store_id' => $this->store_id]);
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

    public function getGoodsPicList()
    {
        return $this->hasMany(PtGoodsPic::className(), ['goods_id' => 'id'])->andWhere(['is_delete' => 0]);
    }
}
