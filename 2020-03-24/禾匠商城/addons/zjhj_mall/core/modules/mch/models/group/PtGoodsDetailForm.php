<?php

namespace app\modules\mch\models\group;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\GoodsShare;
use app\models\PtGoods;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class PtGoodsDetailForm extends MchModel
{
    public $store_id;
    public $colonel;
    public $group_num;
    public $group_time;
    public $attr;
    public $model;
    public $goods_id;

    public $keyword;

    public $individual_share;
    public $share_commission_first;
    public $share_commission_second;
    public $share_commission_third;
    public $share_type;
    public $rebate;

    public $use_attr;
    public $attr_setting_type;//多规格分销佣金类型
    public $attr_member_price_List;
    public $single_share_commission_first;
    public $single_share_commission_second;
    public $single_share_commission_third;
    public $is_level;

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
            [['store_id', 'group_num', 'group_time', 'colonel'], 'required'],
            [['store_id', 'group_num', 'goods_id', 'group_time', 'is_level'], 'integer'],
            [['colonel'], 'number', 'min' => 0],
            [['group_num',], 'integer', 'min' => 2, 'max' => 10000],
            [['group_time',], 'integer', 'min' => 0, 'max' => 10000],
            [['share_commission_first', 'share_commission_second', 'share_commission_third','individual_share'], 'default', 'value' => 0],
            [['attr', 'attr_setting_type', 'individual_share','share_type', ], 'safe'],
            [['attr', 'attr_member_price_List'], 'app\models\common\admin\validator\AttrValidator'],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'default', 'value' => 0],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'number', 'min' => 0, 'max' => 999999],
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
            'share_commission_first' => '一级佣金比例',
            'share_commission_second' => '二级佣金比例',
            'share_commission_third' => '三级佣金比例',
            'rebate' => '自购返利',
            'attr_setting_type' => '多规格分销类型',
            'single_share_commission_first' => '一级佣金',
            'single_share_commission_second' => '二级佣金',
            'single_share_commission_third' => '三级佣金',
            'is_level' => '会员折扣'
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = PtGoods::find()->where(['store_id' => $this->store_id, 'is_delete' => 0]);

        if ($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

        $list = $query->limit($p->limit)->offset($p->offset)->asArray()->all();

        return [
            'code' => 0,
            'data' => array(
                'page_count' => $count,
                'list' => $list,
            ),
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->model->store_id = $this->store_id;
        $this->model->colonel = $this->colonel;
        $this->model->group_num = $this->group_num;
        $this->model->group_time = $this->group_time;
        $this->model->is_level = $this->is_level;

        if ($this->goods_id) {
            $this->model->goods_id = $this->goods_id;
            $id = $this->goods_id;
        } else {
            $id = $this->model->goods_id;
        }

        $goods = PtGoods::find()->select('id,attr,use_attr')->where(['is_delete' => 0, 'id' => $id, 'store_id' => $this->store_id])->one();
        $old = json_decode($goods->attr, true);
        $new = json_decode($this->attr, true);

        foreach ($new as $k1 => $v1) {
            foreach ($old as $k => $v) {
                if ($new['attr_list'] == $old['attr_list']) {
                    if ($new[$k]['price'] > 99999999.99 || $new[$k]['price'] < 0) {
                        return [
                            'code' => 1,
                            'msg' => '阶级团购价超过限制',
                        ];
                    }
                    $old[$k]['price'] = round($new[$k]['price'], 2);
                }
            }
        }

        $goodsDetail = $this->model;
        $this->setAttr($goods, $goodsDetail);

        if ($this->model->save()) {

            //单商品分销设置
            $goodsShare = GoodsShare::find()->where([
                'type' => GoodsShare::SHARE_GOODS_TYPE_PT_STANDARD,
                'relation_id' => $goodsDetail->id,
            ])->one();

            if (!$goodsShare) {
                $goodsShare = new GoodsShare();
            }

            $goodsShare->store_id = $this->store_id;
            $goodsShare->type = GoodsShare::SHARE_GOODS_TYPE_PT_STANDARD;
            $goodsShare->goods_id = $goodsDetail->goods_id;
            $goodsShare->individual_share = $this->individual_share;
            $goodsShare->share_commission_first = $this->share_commission_first;
            $goodsShare->share_commission_second = $this->share_commission_second;
            $goodsShare->share_commission_third = $this->share_commission_third;
            $goodsShare->share_type = $this->share_type;
            $goodsShare->rebate = $this->rebate ? $this->rebate : 0;
            $goodsShare->relation_id = $goodsDetail->id;
            $goodsShare->attr_setting_type = $this->attr_setting_type;
            $goodsShare->save();

            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }

    /**
     * @param Goods $goods
     */
    private function setAttr($goods, $goodsDetail)
    {
        if (!$goods->use_attr) {
            $goodsAttrInfo = \Yii::$app->serializer->decode($this->attr)[0];
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
                    'num' => intval($goodsAttrInfo['num']) ? intval($goodsAttrInfo['num']) : 0,
                    'price' => floatval($goodsAttrInfo['price']) ? floatval($goodsAttrInfo['price']) : 0,
                    'single' => floatval($goodsAttrInfo['single']) ? floatval($goodsAttrInfo['single']) : 0,
                    'no' => $goodsAttrInfo['no'] ? $goodsAttrInfo['no'] : '',
                    'pic' => $goodsAttrInfo['pic'] ? $goodsAttrInfo['pic'] : '',
                ],
            ];

            $levels = $this->attr_member_price_List;
            foreach ($levels as $k => $level) {
                $this->attr[0][$k] = number_format(floatval($level), 2, '.', '');
            }

            // 单规格设置
            if ($this->attr_setting_type == 1) {
                $this->attr[0]['share_commission_first'] = $this->single_share_commission_first;
                $this->attr[0]['share_commission_second'] = $this->single_share_commission_second;
                $this->attr[0]['share_commission_third'] = $this->single_share_commission_third;
            }
        }


        if (empty($this->attr)) {
            return;
        }

        $attrs = is_string($this->attr) ? \Yii::$app->serializer->decode($this->attr) : $this->attr;

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        $new_attr = [];
        foreach ($attrs as $i => $item) {
            $new_attr_item = [
                'attr_list' => [],
                'num' => intval($item['num']),
                'price' => doubleval($item['price']),
                'single' => doubleval($item['single']),
                'pic' => $item['pic'] ? $item['pic'] : '',
                'share_commission_first' => $item['share_commission_first'] ? $item['share_commission_first'] : '',
                'share_commission_second' => $item['share_commission_second'] ? $item['share_commission_second'] : '',
                'share_commission_third' => $item['share_commission_third'] ? $item['share_commission_third'] : '',
            ];

            foreach ($levelList as $level) {
                $keyName = "member" . $level['level'];
                $valueName = $item[$keyName] ? $item[$keyName] : '';
                $new_attr_item[$keyName] = $valueName ? number_format($valueName, 2, '.', '') : $valueName;
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

        $goodsDetail->attr = \Yii::$app->serializer->encode($new_attr);
        $goodsDetail->save();
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
}
