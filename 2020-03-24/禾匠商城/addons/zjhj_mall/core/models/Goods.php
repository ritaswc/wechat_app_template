<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%goods}}".
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
 * @property string $service
 * @property integer $sort
 * @property integer $virtual_sales
 * @property string $cover_pic
 * @property string $video_url
 * @property string $unit
 * @property integer $individual_share
 * @property string $share_commission_first
 * @property string $share_commission_second
 * @property string $share_commission_third
 * @property double $weight
 * @property string $freight
 * @property string $full_cut
 * @property string $integral
 * @property integer $use_attr
 * @property integer $share_type
 * @property integer $quick_purchase
 * @property integer $hot_cakes
 * @property integer $mch_id
 * @property integer $goods_num
 * @property string $cost_price
 * @property integer $member_discount
 * @property string $rebate
 * @property integer $mch_sort
 * @property integer $type
 * @property integer $is_level
 * @property integer $confine_count
 * @property integer $is_negotiable
 */
class Goods extends \yii\db\ActiveRecord
{

    /**
     * 价格面议
     */
    const GOODS_NEGOTIABLE = '价格面议';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'detail', 'attr'], 'required'],
            [['store_id', 'cat_id', 'status', 'addtime', 'is_delete', 'sort', 'individual_share', 'freight', 'use_attr', 'share_type', 'quick_purchase', 'hot_cakes', 'mch_id', 'goods_num', 'member_discount', 'virtual_sales', 'mch_sort', 'type', 'is_level', 'confine_count', 'is_negotiable'], 'integer'],
            [['price', 'original_price', 'share_commission_first', 'share_commission_second', 'share_commission_third', 'weight', 'cost_price', 'rebate'], 'number'],
            [['detail', 'attr', 'cover_pic', 'full_cut', 'integral'], 'string'],
            [['name', 'unit'], 'string', 'max' => 255],
            [['service'], 'string', 'max' => 2000],
            [['video_url'], 'string', 'max' => 2048],
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
            'price' => '售价',
            'original_price' => '原价（只做显示用）',
            'detail' => '商品详情，图文',
            'cat_id' => '商品类别',
            'status' => '上架状态：0=下架，1=上架',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'attr' => '规格的库存及价格',
            'service' => '商品服务选项',
            'sort' => '排序  升序',
            'virtual_sales' => '虚拟销量',
            'cover_pic' => '商品缩略图',
            'video_url' => '视频',
            'unit' => '单位',
            'individual_share' => '是否单独分销设置：0=否，1=是',
            'share_commission_first' => '一级分销佣金比例',
            'share_commission_second' => '二级分销佣金比例',
            'share_commission_third' => '三级分销佣金比例',
            'weight' => '重量',
            'freight' => '运费模板ID',
            'full_cut' => '满减',
            'integral' => '积分设置',
            'use_attr' => '是否使用规格：0=不使用，1=使用',
            'share_type' => '佣金配比 0--百分比 1--固定金额',
            'quick_purchase' => '是否加入快速购买  0--关闭   1--开启',
            'hot_cakes' => '是否加入热销  0--关闭   1--开启',
            'mch_id' => '入驻商户的id',
            'goods_num' => '商品总库存',
            'cost_price' => 'Cost Price',
            'member_discount' => '是否参与会员折扣  0=参与   1=不参与',
            'rebate' => '自购返利',
            'mch_sort' => '多商户自己的排序',
            'type' => '类型 0--商城或多商户 2--砍价商品',
            'is_level' => '是否享受会员折扣',
            'confine_count' => '购买限制:0.不限制|大于0等于限购数量',
            'is_negotiable' => '面议方式',
        ];
    }

    /**
     * 修改商品
     * @return array
     */
    public function saveGoods()
    {
        if ($this->validate()) {
            if ($this->save()) {
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '失败'
                ];
            }
        } else {
            return (new Model())->getErrorResponse($this);
        }
    }

    public function getGoodsPic($index = 0)
    {
        $list = $this->goodsPicList;
        if (!$list) {
            return null;
        }
        return isset($list[$index]) ? $list[$index] : null;
    }

    public static function getGoodsPicStatic($goods_id, $index = 0)
    {
        $goods = Goods::findOne($goods_id);
        if (!$goods) {
            return null;
        }
        return $goods->getGoodsPic($index);
    }

    public function getGoodsCover()
    {
        if ($this->cover_pic) {
            return $this->cover_pic;
        }
        $pic = $this->getGoodsPic(0);
        if ($pic) {
            return $pic->pic_url;
        }
        return null;
    }

    public function getGoodsCoverStatic($goods_id)
    {
        $goods = Goods::findOne($goods_id);
        if ($goods) {
            return $goods->getGoodsCover();
        }
        return null;
    }

    public function getCat()
    {
        return $this->hasOne(Cat::className(), ['id' => 'cat_id'])->where(['store_id' => $this->store_id]);
    }

    public function getBargain()
    {
        return $this->hasOne(BargainGoods::className(), ['goods_id' => 'id']);
    }

    public function getStep()
    {
        return $this->hasOne(StepGoods::className(), ['goods_id' => 'id']);
    }

    public function getMch()
    {
        return $this->hasOne(Mch::className(), ['id' => 'mch_id']);
    }

    public function getGoodsPicList()
    {
        return $this->hasMany(GoodsPic::className(), ['goods_id' => 'id'])->where(['is_delete' => 0]);
    }

    public function getCatList1()
    {
        return $this->hasMany(GoodsCat::className(), ['goods_id' => 'id'])->where(['store_id' => $this->store_id, 'is_delete' => 0]);
    }

    public function getCats()
    {
        return $this->hasMany(Cat::className(), ['id' => 'cat_id'])
            ->via('gc');
    }

    public function getGc()
    {
        return $this->hasMany(GoodsCat::className(), ['goods_id' => 'id']);
    }

    public function getMchCats()
    {
        return $this->hasMany(MchCat::className(), ['id' => 'cat_id'])
            ->via('mchGc');
    }

    public function getMchGc()
    {
        return $this->hasMany(MchGoodsCat::className(), ['goods_id' => 'id']);
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

    /**
     * 获取商品销量
     */
    public function getSalesVolume()
    {
        $res = OrderDetail::find()->alias('od')
            ->select('SUM(od.num) AS sales_volume')
            ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
            ->where(['od.is_delete' => 0, 'od.goods_id' => $this->id, 'o.is_delete' => 0, 'o.is_pay' => 1, 'o.store_id' => Yii::$app->store->id])
            ->asArray()->one();

        return empty($res['sales_volume']) ? 0 : intval($res['sales_volume']);
    }

    /**
     * 减满
     */
    public static function cutFull($goodsList)
    {
        // 将商品ID相同的商品合并
        $newGoodsList = [];
        foreach ($goodsList as $row) {
            if (isset($newGoodsList[$row['goods_id']])) {
                $newGoodsList[$row['goods_id']]['num'] += $row['num'];
                $newGoodsList[$row['goods_id']]['weight'] += $row['weight'] * $row['num'];
                $newGoodsList[$row['goods_id']]['price'] += $row['price'];
            } else {
                $row['weight'] = $row['weight'] * $row['num'];
                $newGoodsList[$row['goods_id']] = $row;
            }
        }
        $resGoodsList = [];
        foreach ($newGoodsList as $val) {
            if ($val['full_cut']) {
                $full_cut = json_decode($val['full_cut'], true);
            } else {
                $full_cut = [
                    'pieces' => 0,
                    'forehead' => 0,
                ];
            }

            if ((empty($full_cut['pieces']) || $val['num'] < ($full_cut['pieces'] ?: 0)) && (empty($full_cut['forehead']) || $val['price'] < ($full_cut['forehead'] ?: 0))) {
                $resGoodsList[] = $val;
            }
        }
        return $resGoodsList;
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
            $goods_card_list[$index]['goods_id'] = $id;
        }
        return $goods_card_list;
    }

    // 规格组
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
     * @param \app\models\Goods $goods
     */
    public static function getCatList($goods)
    {
        $cat_list = [];
        $cat = GoodsCat::findAll(['goods_id' => $goods->id, 'store_id' => $goods->store_id, 'is_delete' => 0]);
        if ($cat) {
            foreach ($cat as $index => $value) {
                $cat_name = Cat::findOne(['id' => $value->cat_id, 'store_id' => $goods->store_id]);
                $cat_list[$index]['cat_id'] = $value->cat_id;
                $cat_list[$index]['cat_name'] = $cat_name->name;
            }
        } else {
            $cat_name = Cat::findOne(['id' => $goods->cat_id]);
            $cat_list[0]['cat_id'] = $goods->cat_id;
            $cat_list[0]['cat_name'] = $cat_name->name;
        }
        return $cat_list;
    }


    public function getCatListAsString()
    {
        $cats = '';
        if ($this->catList1) {
            foreach ($this->catList1 as $value) {
                /** @var GoodsCat $value */
                $cats .= $value->cat->name . ',';
            }
            return trim($cats, ',');
        } else {
            return $this->cat->name;
        }
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

    /**
     * @param \app\models\Goods $goods
     */
    public function getMchCatList()
    {
        $cat_list = [];
        $cat = MchGoodsCat::findAll(['goods_id' => $this->id]);
        if ($cat) {
            foreach ($cat as $index => $value) {
                $cat_name = MchCat::findOne(['id' => $value->cat_id]);
                $cat_list[] = $cat_name->name;
            }
        } else {
            $cat_list[] = "";
        }
        return implode(',', $cat_list);
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

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }


    /**
     * @param $user User
     * @param $goods_id
     * @return false|null|string
     * 查找指定用户指定商品购买数量
     */
    public static function getBuyNum($user, $goods_id)
    {
        $goodsNum = OrderDetail::find()->alias('od')->innerJoin(['o' => Order::tableName()], 'o.id=od.order_id')
            ->where([
                'od.goods_id' => $goods_id, 'store_id' => $user->store_id, 'o.user_id' => $user->id,
                'o.is_cancel' => 0, 'o.is_delete' => 0, 'o.is_show' => 1
            ])->andWhere([
                'or',
                ['o.is_pay' => 1],
                ['o.pay_type' => Order::PAY_TYPE_COD]
            ])->select('SUM(od.num) num')->scalar();
        return $goodsNum;
    }


    /**
     * @return array
     */
    public static function getDefaultAttr($store_id)
    {
        $default_attr_name = '默认';
        $default_attr_group_name = '规格';
        $attr = Attr::findOne([
            'attr_name' => $default_attr_name,
            'is_delete' => 0,
            'is_default' => 1,
        ]);
        $attr_group = null;
        if (!$attr) {
            $attr_group = AttrGroup::findOne([
                'attr_group_name' => $default_attr_group_name,
                'is_delete' => 0,
            ]);
            if (!$attr_group) {
                $attr_group = new AttrGroup();
                $attr_group->store_id = $store_id;
                $attr_group->attr_group_name = $default_attr_group_name;
                $attr_group->is_delete = 0;
                $attr_group->save(false);
            }
            $attr = new Attr();
            $attr->attr_group_id = $attr_group->id;
            $attr->attr_name = $default_attr_name;
            $attr->is_delete = 0;
            $attr->is_default = 1;
            $attr->save(false);
        } else {
            $attr_group = AttrGroup::findOne($attr->attr_group_id);
        }
        return [$attr, $attr_group];
    }

}
