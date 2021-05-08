<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/11
 * Time: 20:26
 */

namespace app\modules\mch\models;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\GoodsShare;
use app\models\MiaoshaGoods;
use app\models\MsGoods;

class MiaoshaGoodsEditForm extends MchModel
{
    public $goods_id;
    public $store_id;
    public $attr;
    public $open_time;
    public $open_date;

    public $buy_max;
    public $buy_limit;
    public $goods;
    public $miaoshaGoods;
    public $stock;
    public $price;

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
    public $miaosha_price;
    public $miaosha_num;
    public $miaosha_pic;
    public $is_level;

    public function rules()
    {
        return [
            [['goods_id', 'open_time', 'open_date',], 'required'],
            [['buy_max','buy_limit'], 'default', 'value' => 0],
            [['buy_max', 'buy_limit'],'integer', 'min' => 0],

            [['store_id', 'buy_limit', 'stock'], 'required'],
            [['store_id','share_type', 'use_attr', 'attr_setting_type', 'is_level'], 'integer'],
            [['attr', 'goods_pic_list','form_list',], 'safe',],
            [['share_commission_first', 'share_commission_second', 'share_commission_third','individual_share','rebate','buy_limit','stock'], 'default', 'value' => 0],
            [['price','share_commission_first', 'share_commission_second', 'share_commission_third','rebate',], 'number', 'min' => 0,'max'=>999999],
            [['buy_limit','stock'],'integer','min'=>0,'max'=>99999],
            [['attr', 'attr_member_price_List'], 'app\models\common\admin\validator\AttrValidator'],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'default', 'value' => 0],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'number', 'min' => 0, 'max' => 999999],
            [['miaosha_price', 'miaosha_num', 'miaosha_pic'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'buy_max' => '限购数量',
            'buy_limit' => '限单',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $msGoods = MsGoods::find()->where(['id' => $this->goods_id, 'store_id' => $this->store_id])->one();
        if (!$msGoods) {
            return [
                'code' => 0,
                'msg' => '该商品不存在，请选择其它商品',
            ];
        }
        $open_date = $this->open_date;
        $open_time = $this->open_time;
        \Yii::$app->request->getHostInfo();

        foreach ($open_date as $date) {
            foreach ($open_time as $time) {

                $miaoshaGoods = MiaoshaGoods::findOne([
                    'goods_id' => $this->goods_id,
                    'start_time' => intval($time),
                    'open_date' => $date,
                    'is_delete' => 0,
                ]);

                \Yii::trace("---->" . ($miaoshaGoods == null));
                if (!$miaoshaGoods) {
                    $miaoshaGoods = new MiaoshaGoods();
                    $miaoshaGoods->store_id = $this->store_id;
                    $miaoshaGoods->goods_id = $this->goods_id;
                    $miaoshaGoods->start_time = intval($time);
                    $miaoshaGoods->open_date = $date;
                    $miaoshaGoods->is_delete = 0;
                }
                $miaoshaGoods->buy_max = $this->buy_max;
                $miaoshaGoods->is_level = $this->is_level;
                $miaoshaGoods->buy_limit = $this->buy_limit;
                $miaoshaGoods->save();

                $this->setAttr($msGoods, $miaoshaGoods);
                //单商品分销设置
                $goodsShare = GoodsShare::find()->where(['relation_id' => $miaoshaGoods->id])->one();
                if (!$goodsShare) {
                    $goodsShare = new GoodsShare();
                }

                $goodsShare->store_id = $this->store_id;
                $goodsShare->type = GoodsShare::SHARE_GOODS_TYPE_MS;
                $goodsShare->goods_id = $msGoods->id;
                $goodsShare->individual_share = $this->individual_share;
                $goodsShare->share_commission_first = $this->share_commission_first;
                $goodsShare->share_commission_second = $this->share_commission_second;
                $goodsShare->share_commission_third = $this->share_commission_third;
                $goodsShare->share_type = $this->share_type;
                $goodsShare->rebate = $this->rebate;
                $goodsShare->attr_setting_type = $this->attr_setting_type;
                $goodsShare->relation_id = $miaoshaGoods->id;
                $goodsShare->save();
            }
        }

        return [
            'code' => 0,
            'msg' => '保存成功',
            'data' => [
                'return_url' => \Yii::$app->urlManager->createUrl(['mch/miaosha/goods-detail', 'goods_id' => $this->goods_id]),
            ],
        ];

    }

    /**
     * @param Goods $goods
     */
    private function setAttr($goods, $miaoshaGoods)
    {
        if (!$goods->use_attr) {
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
                    'num' => intval($this->stock) ? intval($this->stock) : 0,
                    'price' => floatval($this->price) ? floatval($this->price) : 0,
                    'miaosha_price' => $this->miaosha_price[0] ? $this->miaosha_price[0] : 0,
                    'miaosha_num' => $this->miaosha_num[0] ? $this->miaosha_num[0] : 0,
                    'sell_num' => $this->attr[0]['sell_num'] ? $this->attr[0]['sell_num'] : 0,
                ],
            ];

            $levels = $this->attr_member_price_List;
            foreach ($levels as $k => $level) {
                $this->attr[0][$k] = $level;
            }


            // 单规格设置
            if ($this->attr_setting_type == 1) {
                $this->attr[0]['share_commission_first'] = $this->single_share_commission_first;
                $this->attr[0]['share_commission_second'] = $this->single_share_commission_second;
                $this->attr[0]['share_commission_third'] = $this->single_share_commission_third;
            }
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
                'pic' => $this->miaosha_pic[$i] ? $this->miaosha_pic[$i] : '',
                'miaosha_price' => $this->miaosha_price[$i] ? $this->miaosha_price[$i] : 0,
                'miaosha_num' => $this->miaosha_num[$i] ? $this->miaosha_num[$i] : 0,
                'sell_num' => $item['sell_num'] ? $item['sell_num'] : 0,
                'share_commission_first' => $item['share_commission_first'] ? $item['share_commission_first']: 0,
                'share_commission_second' => $item['share_commission_second'] ? $item['share_commission_second']: 0,
                'share_commission_third' => $item['share_commission_third'] ? $item['share_commission_third']: 0,
            ];

            foreach ($levelList as $level) {
                $keyName = "member" . $level['level'];
                $valueName = $item[$keyName] ? $item[$keyName]: '';
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

        $miaoshaGoods->attr = \Yii::$app->serializer->encode($new_attr);
        $miaoshaGoods->save();
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
