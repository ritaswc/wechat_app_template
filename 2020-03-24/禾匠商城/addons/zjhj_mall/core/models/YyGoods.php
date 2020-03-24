<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yy_goods}}".
 *
 * @property string $id
 * @property string $name
 * @property string $price
 * @property string $original_price
 * @property string $detail
 * @property string $cat_id
 * @property integer $status
 * @property string $service
 * @property string $sort
 * @property string $virtual_sales
 * @property string $cover_pic
 * @property string $addtime
 * @property integer $is_delete
 * @property string $sales
 * @property string $shop_id
 * @property string $store_id
 * @property string $buy_limit
 * @property string $stock
 * @property string $attr
 * @property integer $use_attr
 * @property integer $is_level
 * @property string $video_url
 */
class YyGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yy_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'original_price', 'detail', 'service', 'store_id', 'buy_limit', 'stock'], 'required'],
            [['price', 'original_price'], 'number'],
            [['detail', 'cover_pic', 'attr'], 'string'],
            [['cat_id', 'status', 'sort', 'virtual_sales', 'addtime', 'is_delete', 'sales', 'store_id', 'buy_limit', 'stock', 'use_attr'], 'integer'],
            [['name', 'shop_id', 'video_url'], 'string', 'max' => 255],
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
            'price' => '预约金额',
            'original_price' => '原价',
            'detail' => '商品详情，图文',
            'cat_id' => '商品分类',
            'status' => '上架状态【1=> 上架，2=> 下架】',
            'service' => '服务选项',
            'sort' => '商品排序 升序',
            'virtual_sales' => '虚拟销量',
            'cover_pic' => '商品缩略图',
            'addtime' => '添加时间',
            'is_delete' => '是否删除',
            'sales' => '实际销量',
            'shop_id' => '门店id',
            'store_id' => 'Store ID',
            'buy_limit' => '限购次数',
            'stock' => '库存',
            'attr' => '规格',
            'use_attr' => '是否启用规格',
            'is_level' => '是否享受会员折扣 0-不享受 1--享受',
            'video_url' => '商品视频',
        ];
    }

    /**
     * @return static[]
     * 商品图集
     */
    public function goodsPicList()
    {
        return YyGoodsPic::findAll(['goods_id' => $this->id, 'is_delete' => 0]);
    }

    public static function getShop($shop_id_list, $store_id)
    {
        if ($shop_id_list) {
            if ($shop_id_list == '-1') {
                $shopList = [];
                $shopNum = 0;
            } else {
                $shopId = explode(',', trim($shop_id_list, ','));
                $shopList = Shop::find()
                    ->andWhere(['id' => $shopId])
                    ->andWhere(['store_id' => $store_id, 'is_delete' => 0])
                    ->asArray()
                    ->all();
                $shopNum = count($shopList);
            }
        } else {
            $shopList = Shop::find()
                ->andWhere(['store_id' => $store_id, 'is_delete' => 0])
                ->asArray()
                ->all();
            $shopNum = count($shopList);
        }
        return [
            'list' => $shopList,
            'num' => $shopNum
        ];
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
            ->where(['ag.id' => Attr::find()->select('attr_group_id')->distinct()->where(['id' => $att_id])])
            ->limit(1)->one();
        if (!$attr_group) {
            return $attr_group;
        }
        Yii::$app->cache->set($cache_key, $attr_group, 10);
        return $attr_group;
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
     * '[{"attr_group_id":17,"attr_id":417,"attr_group_name":"规格","attr_name":"xxx"},{"attr_group_id":4,"attr_id":420,"attr_group_name":"套餐","attr_name":"C套餐"}]'
     * @return [type] [description]
     */
    public function checkAttr($attr)
    {
        if ($this->use_attr==0) {
            //兼容老版本
            if ($this->attr==null) {
                $attr = [];
                $attr[0]['attr_id'] = 0;
                $attr[0]['attr_group_name'] = '规格';
                $attr[0]['attr_name'] = '默认';
            } else {
                $attr = json_decode($attr, true);
                foreach ($attr as &$v) {
                    unset($v['attr_group_id']);
                };
                unset($v);
            }
            return [
                'attr' => \Yii::$app->serializer->encode($attr),
                'num' => $this->stock,
                'price' => $this->price
            ];
        } else {
            $attr = json_decode($attr, true);
            $attr_id_list = [];
            foreach ($attr as &$v) {
                unset($v['attr_group_id']);
                $attr_id_list[] = $v['attr_id'];
            };
            unset($v);

            $goods = $this->getAttrInfo($attr_id_list);
            return [
                'attr' => \Yii::$app->serializer->encode($attr),
                'num' => $goods['num'],
                'price' => $goods['price']
            ];
        }
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
                // if (!$attr_rows[$i]['price']) {
                //     $attr_rows[$i]['price'] = $this->price;
                // }
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

    public function getGoodsPicList()
    {
        return $this->hasMany(YyGoodsPic::className(), ['goods_id' => 'id']);
    }
}
