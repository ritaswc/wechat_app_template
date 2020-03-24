<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/10
 * Time: 16:01
 */

namespace app\modules\mch\models\integralmall;

use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\MchModel;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\IntegralGoods;
use yii\data\Pagination;
use app\models\IntegralCat;

class IntegralGoodsForm extends MchModel
{
    public $store_id;
    public $name;
    public $price;
    public $original_price;
    public $detail;
    public $cat_id;
    public $attr;
    public $sort;
    public $virtual_sales;
    public $cover_pic;
    public $unit;
    public $weight;
    public $freight;
    public $use_attr;
    public $goods_num;
    public $integral;
    public $service;
    public $cost_price;
    public $goods_pic_list;
    public $goods;
    public $model;
    public $user_num;
    public $id;
    public $goods_no;

    public function rules()
    {
        return [
            [['store_id', 'cat_id', 'name', 'cover_pic', 'goods_pic_list', 'integral', 'original_price', 'detail', 'user_num'], 'required'],
            [['store_id', 'cat_id', 'freight', 'use_attr'], 'integer'],
            [['detail', 'cover_pic', 'goods_no'], 'string'],
            [['name', 'unit'], 'string', 'max' => 255],
            [['service'], 'string', 'max' => 2000],
            [['goods_pic_list', 'attr', 'model'], 'safe'],
            [['sort', 'virtual_sales', 'goods_num', 'user_num'], 'integer', 'min' => 0, 'max' => 999999],
            [['sort', 'virtual_sales', 'goods_num', 'cost_price', 'weight', 'price', 'original_price', 'goods_no'], 'default', 'value' => 0],
            [['cost_price', 'weight', 'price', 'original_price'], 'number', 'min' => 0],
            [['integral'], 'integer', 'min' => 1, 'max' => 999999],
            [['attr'], 'app\modules\mch\models\AttrValidator']
        ];
    }

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
            'virtual_sales' => '已出售量',
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
            'user_num' => '用户每日可兑换数量',
            'goods_no' => '商品货号',
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            if (!is_array($this->goods_pic_list) || empty($this->goods_pic_list) || count($this->goods_pic_list) == 0 || !$this->goods_pic_list[0]) {
                return [
                    'code' => 1,
                    'msg' => '商品图片不能为空',
                ];
            }
            if ($this->use_attr == 0 && ($this->goods_num === null || $this->goods_num === '')) {
                return [
                    'code' => 1,
                    'msg' => '请填写商品库存',
                ];
            }
            if (!$this->original_price) {
                $this->original_price = $this->price;
            }
            if ($this->original_price > 99999999.99) {
                return [
                    'code' => 1,
                    'msg' => '商品原价超过限制',
                ];
            }
            if (!$this->cost_price) {
                $this->cost_price = $this->price;
            }
            if ($this->cost_price > 99999999.99) {
                return [
                    'code' => 1,
                    'msg' => '商品成本价超过限制',
                ];
            }
            if ($this->price > 99999999.99) {
                return [
                    'code' => 1,
                    'msg' => '商品售价超过限制',
                ];
            }
            if ($this->use_attr == 1) {
                if ($this->attr == null) {
                    return [
                        'code' => 1,
                        'msg' => '请填写商品规格',
                    ];
                }
            }
            $goods = $this->goods;
            if ($goods->isNewRecord) {
                $goods->is_delete = 0;
                $goods->addtime = time();
                $goods->status = 0;
            }
            if (!$this->virtual_sales) {
                $this->virtual_sales = 0;
            }
            if (!$this->price) {
                $this->price = 0;
            }
            $goods->use_attr = $this->use_attr;
            $this->goods_pic_list = \Yii::$app->serializer->encode($this->goods_pic_list);
            $this->attr = \Yii::$app->serializer->encode($this->setAttr());
            $_this_attributes = $this->attributes;
            $goods->attributes = $_this_attributes;

            //去除部分emoji
            function userTextEncode($str)
            {
                if (!is_string($str)) return $str;
                if (!$str || $str == 'undefined') return '';
                $text = json_encode($str); //暴露出unicode
                $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
                    return addslashes($str[0]);
                }, $text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
                return json_decode($text);
            }

            ;
            $goods->detail = preg_replace('/\\\u[a-z0-9]{4}/', '', userTextEncode($_this_attributes['detail']));

            if ($goods->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return $this->getErrorResponse($goods);
            }
        } else {
            return $this->errorResponse;
        }
    }

    public function addGoods()
    {
        $goods = $this->goods;
        $model = $this->model;
        if ($goods->isNewRecord) {
            $goods->is_delete = 0;
            $goods->addtime = time();
            $goods->status = 0;
        }
        $goods->store_id = $model->store_id;
        $goods->name = $model->name;
        $goods->price = $model->price ? $model->price : 0;
        $goods->original_price = $model->original_price;
        $goods->detail = $model->detail;
        $goods->cat_id = $model->cat_id;
        $goods->user_num = $model->user_num;
        $goods->sort = $model->sort;
        $goods->virtual_sales = $model->virtual_sales;
        $goods->cover_pic = $model->cover_pic;
        $goods->unit = $model->unit;
        $goods->weight = $model->weight;
        $goods->freight = $model->freight;
        $goods->use_attr = $model->use_attr;
        $goods->goods_num = $model->goods_num;
        $goods->integral = $model->integral;
        $goods->service = $model->service;
        $goods->goods_pic_list = $model->goods_pic_list;
        $this->use_attr = $model->use_attr;
        $this->store_id = $model->store_id;
        $this->attr = json_encode($this->setAttr(), JSON_UNESCAPED_UNICODE);
        $goods->attr = $this->attr;
        if ($goods->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($goods);
        }
    }

    private function setAttr()
    {
        if ($this->use_attr == 0) {
            list($default_attr, $default_attr_group) = $this->getDefaultAttr();
            $this->attr = [
                [
                    'attr_list' => [
                        [
                            'attr_group_name' => $default_attr_group->attr_group_name,
                            'attr_id' => $default_attr->id,
                            'attr_name' => $default_attr->attr_name,
                        ],
                    ],
                    'num' => intval($this->goods_num) ? intval($this->goods_num) : 0,
                    'price' => 0,
                    'integral' => 0,
                    'single' => 0,
                    'no' => $this->goods_no
                ],
            ];
        }
        if (empty($this->attr) || !is_array($this->attr)) {
            return;
        }

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        $new_attr = [];
        foreach ($this->attr as $i => $item) {
            $new_attr_item = [
                'attr_list' => [],
                'num' => intval($item['num']),
                'price' => doubleval($item['price']),
                'integral' => doubleval($item['integral']),
                'single' => doubleval($item['single']),
                'no' => $item['no'] ? $item['no'] : '',
                'pic' => $item['pic'] ? $item['pic'] : '',
                'share_commission_first' => $item['share_commission_first'] ? $item['share_commission_first'] : '',
                'share_commission_second' => $item['share_commission_second'] ? $item['share_commission_second'] : '',
                'share_commission_third' => $item['share_commission_third'] ? $item['share_commission_third'] : '',
            ];


            foreach ($levelList as $level) {
                $keyName = "member" . $level['level'];
                $valueName = $item[$keyName] ? $item[$keyName] : '';
                $new_attr_item[$keyName] = $valueName;
            }

            foreach ($item['attr_list'] as $a) {
                $attr_group_model = AttrGroup::findOne(['store_id' => $this->store_id, 'attr_group_name' => $a['attr_group_name'], 'is_delete' => 0]);
                if (!$attr_group_model) {
                    $attr_group_model = new AttrGroup();
                    $attr_group_model->attr_group_name = $a['attr_group_name'];
                    $attr_group_model->store_id = $this->store_id;
                    $attr_group_model->is_delete = 0;
                    $attr_group_model->save();
                }
                $attr_model = Attr::findOne(['attr_group_id' => $attr_group_model->id, 'attr_name' => $a['attr_name'], 'is_delete' => 0]);
                if (!$attr_model) {
                    $attr_model = new Attr();
                    $attr_model->attr_name = $a['attr_name'];
                    $attr_model->attr_group_id = $attr_group_model->id;
                    $attr_model->is_delete = 0;
                    $attr_model->save();
                }
                $new_attr_item['attr_list'][] = [
                    'attr_id' => $attr_model->id,
                    'attr_name' => $attr_model->attr_name,
                ];
            }
            $new_attr[] = $new_attr_item;
        }
        return $new_attr;
    }


    /**
     * @return array
     */
    private function getDefaultAttr()
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
                $attr_group->store_id = $this->store_id;
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

    public function getList($store_id)
    {
        $query = IntegralGoods::find()->alias('v')
            ->where(['v.is_delete' => 0, 'v.store_id' => $store_id])
            ->leftJoin(IntegralCat::tableName() . ' c', 'c.id=v.cat_id');
//        if ($this->keyword)
//            $query->andWhere(['like', 'v.title', $this->keyword]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 15]);
        $list = $query->orderBy(['v.sort' => SORT_ASC, 'v.addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)
            ->select([
                'v.*', 'c.name as cat_name'
            ])->asArray()->all();

        foreach ($list as $key => $item) {
            $goodsNum = 0;
            if (isset($item['attr'])) {
                $attrs = \Yii::$app->serializer->decode($item['attr']);
                foreach ($attrs as $attr) {
                    $goodsNum += $attr['num'];
                }
            }
            $list[$key]['goods_num'] = $goodsNum;
        }

//        foreach ($list as $index => $value) {
//
//        }
        return [$list, $p, $count];
    }

    public function search()
    {
        $inGoods = IntegralGoods::find()->where(['id' => $this->id, 'store_id' => $this->store_id, 'is_delete' => 0, 'status' => 1])->asArray()->one();
        $goods = IntegralGoods::findOne(['id' => $this->id, 'store_id' => $this->store_id, 'is_delete' => 0, 'status' => 1]);

        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品已删除或已下架'
            ];
        }
        $service_list = explode(',', $goods->service);
        $new_service_list = [];
        if (is_array($service_list)) {
            foreach ($service_list as $item) {
                $item = trim($item);
                if ($item) {
                    $new_service_list[] = $item;
                }
            }
        }
        $inGoods['goods_pic_list'] = json_decode($inGoods['goods_pic_list']);
        $inGoods['attr'] = json_decode($inGoods['attr']);
        $inGoods['service_list'] = $new_service_list;
        $inGoods['num'] = 0;
        $inGoods['type'] = 0;
        return [
            'code' => 0,
            'data' => (object)[
                'goods' => $inGoods,
                'attr_group_list' => $goods->getAttrGroupList(),
            ],
        ];
    }
}
