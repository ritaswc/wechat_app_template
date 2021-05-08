<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/7
 * Time: 12:59
 */

namespace app\modules\mch\models;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\Goods;
use app\models\GoodsCard;
use app\models\GoodsCat;
use app\models\GoodsPic;
use app\modules\mch\events\goods\BaseAddGoodsEvent;
use Hejiang\Event\EventArgument;
use yii\data\Pagination;

class GoodsForm extends MchModel
{
    public $goods;

    public $store_id;
    public $name;
    public $goods_pic_list;
    public $cat_id;
    public $price;
    public $original_price;
    public $service;
    public $detail;
    public $sort;
    public $virtual_sales;

    public $cover_pic;
    public $video_url;

    public $attr;
    public $unit;

    public $individual_share;
    public $share_commission_first;
    public $share_commission_second;
    public $share_commission_third;
    public $weight;
    public $freight;

    public $full_cut;
    public $integral;
    public $goods_card;

    public $goods_num;
    public $use_attr;
    public $share_type;
    public $quick_purchase;
    public $hot_cakes;
    public $cost_price;
    public $rebate;
    public $goods_no;
    public $plugins; // 插件提交的数据
    public $plugin; // 插件类型
    public $is_level; // 是否享受会员折扣
    public $confine_count;
    public $is_negotiable;
    public $attr_setting_type;//多规格分销佣金类型
    public $attr_member_price_List;
    public $single_share_commission_first;
    public $single_share_commission_second;
    public $single_share_commission_third;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'service', 'unit', 'goods_no'], 'trim'],
            [['store_id', 'name', 'price', 'cat_id', 'detail', 'goods_pic_list', 'cover_pic', 'attr_setting_type'], 'required'],
            [['store_id', 'share_type', 'quick_purchase', 'hot_cakes', 'is_level', 'is_negotiable'], 'integer'],
            [['price', 'original_price'], 'number', 'min' => 0.01, 'max' => 99999999],
            [['detail', 'service', 'cover_pic', 'video_url', 'goods_no'], 'string'],
            [['name', 'goods_no', 'unit'], 'string', 'max' => 255],
            [['sort'], 'default', 'value' => 1000],
            [['attr', 'individual_share', 'full_cut', 'integral', 'goods_card'], 'safe',],
            [['share_commission_first', 'share_commission_second', 'share_commission_third', 'freight', 'rebate', 'virtual_sales', 'individual_share', 'goods_no', 'confine_count', 'plugin', 'cost_price', 'quick_purchase'], 'default', 'value' => 0],
            [['share_commission_first', 'share_commission_second', 'share_commission_third', 'rebate', 'weight', 'cost_price'], 'number', 'min' => 0, 'max' => 99999999],
            [['goods_num', 'virtual_sales', 'sort', 'freight', 'confine_count'], 'integer', 'min' => 0, 'max' => 99999999],
            [['use_attr', 'plugins'], 'safe'],
            [['attr', 'attr_member_price_List'], 'app\models\common\admin\validator\AttrValidator'],
            [['is_negotiable'], 'default', 'value' => 0],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'default', 'value' => 0],
            [['single_share_commission_first', 'single_share_commission_second', 'single_share_commission_third'], 'number', 'min' => 0, 'max' => 99999999],
        ];
    }

    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => '商品名称',
            'price' => '售价',
            'original_price' => '原价（只做显示用）',
            'detail' => '图文详情',
            'cat_id' => '商品分类',
            'status' => '上架状态：0=下架，1=上架',
            'goods_pic_list' => '商品图片',
            'sort' => '排序',
            'virtual_sales' => '已出售量',
            'service' => '商品服务选项',
            'cover_pic' => '商品缩略图',
            'video_url' => '视频',
            'unit' => '单位',
            'share_commission_first' => '一级佣金比例',
            'share_commission_second' => '二级佣金比例',
            'share_commission_third' => '三级佣金比例',
            'weight' => '重量',
            'freight' => '运费规则ID',
            'full_cut' => '满减',
            'integral' => '积分设置',
            'goods_num' => '商品库存',
            'cost_price' => '成本价',
            'rebate' => '自购返利',
            'is_level' => '是否参与会员折扣',
            'confine_count' => '限购数量',
            'goods_no'=> '货号',
            'is_negotiable' => '是否面议',
            'attr_setting_type' => '多规格分销类型',
            'single_share_commission_first' => '一级佣金',
            'single_share_commission_second' => '二级佣金',
            'single_share_commission_third' => '三级佣金',
        ];
    }

    /**
     *
     */
    public function getList($store_id)
    {
        $query = Goods::find()
            ->alias('g')
            ->andWhere(['g.is_delete' => 0, 'g.store_id' => $store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

        $list = $query
            ->select(['g.*', 'c.name AS cname'])
            ->leftJoin('{{%cat}} c', 'g.cat_id=c.id')
            ->orderBy('g.sort ASC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();
        return [$list, $p];
    }


    // 获取商品小程序码
    /**
     * 编辑
     * @return array
     */
    public function save()
    {
        if ($this->validate()) {
            if (!is_array($this->goods_pic_list) || empty($this->goods_pic_list) || count($this->goods_pic_list) == 0 || !$this->goods_pic_list[0]) {
                return [
                    'code' => 1,
                    'msg' => '商品图片不能为空',
                ];
            }

            if($this->full_cut['pieces'] && $this->full_cut['pieces'] > 99999999.99){
                return [
                    'code' => 1,
                    'msg' => '满件包邮售价的值必须不大于99999999',
                ];
            }
            if($this->full_cut['forehead'] && $this->full_cut['forehead'] > 99999999.99){
                return [
                    'code' => 1,
                    'msg' => '满额包邮的值必须不大于99999999',
                ];
            }
            if($this->integral['give'] && $this->integral['give'] > 99999999){
                return [
                    'code' => 1,
                    'msg' => '积分赠送的值必须不大于99999999',
                ];
            }
            if($this->integral['forehead'] && $this->integral['forehead'] > 99999999.99){
                return [
                    'code' => 1,
                    'msg' => '积分抵扣的值必须不大于99999999',
                ];
            }


            if (!$this->use_attr && ($this->goods_num === null || $this->goods_num === '')) {
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


            // 商品规格有特殊符不能提交
            if (isset($this->attr) && count($this->attr) > 0 && $this->use_attr) {
                foreach ($this->attr as $item) {
                    if (preg_match("/[\'=]|\\\|\"|\|/", $item['no'])) {
                        return [
                            'code' => 1,
                            'msg' => '商品规格组、规格名称、规格详情不能包含\',",\\,=等特殊符',
                        ];
                    }

                    foreach ($item['attr_list'] as $i) {
                        foreach ($i as $i2) {
                            if (preg_match("/[\'=]|\\\|\"|\|/", $i2)) {
                                return [
                                    'code' => 1,
                                    'msg' => '商品规格组、规格名称、规格详情不能包含\',",\\,=等特殊符',
                                ];
                            }
                        }
                    }
                }
            }

            $goods = $this->goods;
            if ($goods->isNewRecord) {
                $goods->is_delete = 0;
                $goods->addtime = time();
                $goods->status = 0;
                $goods->attr = \Yii::$app->serializer->encode([]);
            }

            $this->full_cut = \Yii::$app->serializer->encode($this->full_cut);
            if (!isset($this->integral['more'])) {
                $this->integral['more'] = '';
            }

            $this->integral = \Yii::$app->serializer->encode($this->integral);

            $_this_attributes = $this->attributes;

            unset($_this_attributes['attr']);

            $this->cat_id = array_unique($this->cat_id);

            foreach ($this->cat_id as $index => $value) {
                if (!$value) {
                    return [
                        'code' => 1,
                        'msg' => '请选择分类'
                    ];
                }
            }
            $cat_id = $this->cat_id;
            $_this_attributes['cat_id'] = 0;
            $goods->attributes = $_this_attributes;

            //去除部分emoji
            function userTextEncode($str){
                if(!is_string($str)) return $str;
                if(!$str || $str=='undefined')return '';
                $text = json_encode($str); //暴露出unicode
                $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
                    return addslashes($str[0]);
                   },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
                return json_decode($text);
            };

            $goods->detail = preg_replace('/\\\u[a-z0-9]{4}/', '', userTextEncode($_this_attributes['detail']));

            $goods->quick_purchase = $this->quick_purchase;
            $goods->is_level = $this->is_level;
            $goods->hot_cakes = $this->hot_cakes;
            $goods->attr_setting_type = $this->attr_setting_type;
            $goods->use_attr = $this->use_attr ? 1 : 0;
            $t = \Yii::$app->db->beginTransaction();
            $goods->type = get_plugin_type();
            //步数宝编辑取消会员价
            if ($goods->type === 5) {
                $goods->is_level = 0;
            }
            if ($goods->save()) {
                //多分类设置
                GoodsCat::updateAll(['is_delete' => 1], ['goods_id' => $goods->id]);
                foreach ($cat_id as $index => $value) {
                    $cat = new GoodsCat();
                    $cat->goods_id = $goods->id;
                    $cat->store_id = $goods->store_id;
                    $cat->addtime = time();
                    $cat->cat_id = $value;
                    $cat->is_delete = 0;
                    $cat->save();
                }

                //商品图片保存
                GoodsPic::updateAll(['is_delete' => 1], ['goods_id' => $goods->id]);
                foreach ($this->goods_pic_list as $pic_url) {
                    $goods_pic = new GoodsPic();
                    $goods_pic->goods_id = $goods->id;
                    $goods_pic->pic_url = $pic_url;
                    $goods_pic->is_delete = 0;
                    $goods_pic->save();
                }
                $this->setAttr($goods);

                //商品卡券保存
                GoodsCard::updateAll(['is_delete' => 1], ['goods_id' => $goods->id]);
                if ($this->goods_card) {
                    foreach ($this->goods_card as $card_id) {
                        $goods_card = new GoodsCard();
                        $goods_card->goods_id = $goods->id;
                        $goods_card->card_id = $card_id;
                        $goods_card->is_delete = 0;
                        $goods_card->addtime = time();
                        $goods_card->save();
                    }
                }

                $args = new EventArgument();
                $args['data'] = $this->plugins;
                $args['goods'] = $goods;
                $args['post'] = true;
                \Yii::$app->eventDispatcher->dispatch(new BaseAddGoodsEvent(), $args);
                $results = $args->getResults();
                if (is_array($results) && count($results) > 0) {
                    foreach ($results as $result) {
                        if ($result['code'] == 0) {
                            $t->commit();
                            return [
                                'code' => 0,
                                'msg' => '保存成功',
                            ];
                        } else {
                            $t->rollBack();
                            return $result;
                        }
                    }
                } else {
                    $t->commit();
                    return [
                        'code' => 0,
                        'msg' => '保存成功',
                    ];
                }
            } else {
                $t->rollBack();
                return $this->getErrorResponse($goods);
            }
        } else {
            return $this->errorResponse;
        }
    }

    /**
     * @param Goods $goods
     */
    private function setAttr($goods)
    {
        if (!$this->use_attr) {
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
                    'no' => $this->goods_no,
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
                'no' => $item['no'] ? $item['no'] : '',
                'pic' => $item['pic'] ? $item['pic'] : '',
                'share_commission_first' => $item['share_commission_first'] ? $item['share_commission_first'] : '',
                'share_commission_second' => $item['share_commission_second'] ? $item['share_commission_second'] : '',
                'share_commission_third' => $item['share_commission_third'] ? $item['share_commission_third'] : '',
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

        $goods->attr = \Yii::$app->serializer->encode($new_attr);
        $goods->save();
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
