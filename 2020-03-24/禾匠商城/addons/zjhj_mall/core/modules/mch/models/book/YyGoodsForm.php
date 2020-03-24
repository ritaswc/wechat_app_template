<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/11/22
 * Time: 17:35
 */

namespace app\modules\mch\models\book;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\GoodsShare;
use app\models\YyForm;
use app\models\YyGoods;
use app\models\YyGoodsPic;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

/**
 * @property YyGoods $goods;
 * @property GoodsShare $goods_share;
 */
class YyGoodsForm extends MchModel
{
    public $goods;
    public $goods_share;

    public $goods_pic_list;

    public $name;
    public $store_id;
    public $original_price;
    public $price;
    public $detail;
    public $cat_id;

    public $service;
    public $sort;
    public $virtual_sales;
    public $cover_pic;
    public $buy_limit;
    public $stock;

    public $shop_id;

    public $form_list = [];

    public $individual_share;
    public $share_commission_first;
    public $share_commission_second;
    public $share_commission_third;
    public $share_type;
    public $rebate;

    public $attr;
    public $use_attr;
    public $attr_setting_type;//多规格分销佣金类型
    public $attr_member_price_List;
    public $single_share_commission_first;
    public $single_share_commission_second;
    public $single_share_commission_third;
    public $is_level;
    public $video_url;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'original_price', 'detail', 'service', 'store_id', 'buy_limit', 'stock', 'is_level'], 'required'],
            [['detail', 'cover_pic', 'video_url'], 'string'],
            [['cat_id', 'store_id','share_type', 'use_attr', 'attr_setting_type'], 'integer'],
            [['name','shop_id'], 'string', 'max' => 255],
            [['service'], 'string', 'max' => 2000],
            [['attr', 'goods_pic_list','form_list',], 'safe',],
            [['share_commission_first', 'share_commission_second', 'share_commission_third','individual_share','rebate','virtual_sales','buy_limit','stock','sort'], 'default', 'value' => 0], 
            [['price', 'original_price','share_commission_first', 'share_commission_second', 'share_commission_third','rebate',], 'number', 'min' => 0,'max'=>999999],
            [['buy_limit','stock','virtual_sales','sort'],'integer','min'=>0,'max'=>99999],
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
            'goods_pic_list' => '商品图集',
            'id' => 'ID',
            'name' => '商品名称',
            'price' => '预约金额',
            'original_price' => '原价',
            'detail' => '商品详情，图文',
            'cat_id' => '商品分类',
            'status' => '上架状态【1=> 上架，2=> 下架】',
            'service' => '服务内容',
            'sort' => '商品排序',
            'virtual_sales' => '已出售量',
            'cover_pic' => '商品缩略图',
            'addtime' => '添加时间',
            'is_delete' => '是否删除',
            'sales' => '实际销量',
            'shop_id' => '门店id',
            'store_id' => 'Store ID',
            'buy_limit' => '限购次数',
            'stock' => '库存',
            'share_commission_first' => '一级佣金比例',
            'share_commission_second' => '二级佣金比例',
            'share_commission_third' => '三级佣金比例',
            'rebate'=>'自购返利',
            'attr' =>'规格',
            'attr_setting_type' => '多规格分销类型',
            'single_share_commission_first' => '一级佣金',
            'single_share_commission_second' => '二级佣金',
            'single_share_commission_third' => '三级佣金',
            'is_level' => '会员折扣',
            'video_url' => '商品视频'
        ];
    }

    /**
     * @param $store_id
     * @return array
     * 商品列表
     */
    public function getList($store_id)
    {
        $query = YyGoods::find()
            ->alias('g')
            ->andWhere(['g.is_delete' => 0, 'g.store_id' => $store_id])
            ->select(['g.*', 'c.name AS cname'])
            ->leftJoin('{{%yy_cat}} c', 'g.cat_id=c.id');
        $cat = \Yii::$app->request->get('cat');
        if ($cat) {
            $query->andWhere(['cat_id'=>$cat]);
        }
        $keyword = \Yii::$app->request->get('keyword');
        if (trim($keyword)) {
            $query->andWhere([
                'OR',
                ['LIKE', 'g.name', $keyword],
                ['LIKE', 'c.name', $keyword],
            ]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

        $list = $query
            ->orderBy('g.sort ASC')
            ->offset($p->offset)
            ->asArray()
            ->limit($p->limit)
            ->all();
        return [$list, $p];
    }

    /**
     * @return array
     * 商品编辑
     */
    public function save()
    {
        if ($this->validate()) {
            if (!is_array($this->goods_pic_list) || empty($this->goods_pic_list) || count($this->goods_pic_list) == 0) {
                return [
                    'code' => 1,
                    'msg' => '商品图片不能为空',
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
            if ($this->price > 99999999.99) {
                return [
                    'code' => 1,
                    'msg' => '商品售价超过限制',
                ];
            }

            $goods = $this->goods;
            if ($goods->isNewRecord) {
                $goods->is_delete = 0;
                $goods->addtime = time();
                $goods->status = 2;
                $goods->sales = 0;
                $goods->attr = \Yii::$app->serializer->encode([]);
            }

            $_this_attributes = $this->attributes;
            unset($_this_attributes['attr']);

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

            $goods->use_attr = $this->use_attr ? 1 : 0;
            $goods->is_level = $this->is_level;
            if ($goods->save()) {
                YyGoodsPic::updateAll(['is_delete' => 1], ['goods_id' => $goods->id]);
                foreach ($this->goods_pic_list as $pic_url) {
                    $goods_pic = new YyGoodsPic();
                    $goods_pic->goods_id = $goods->id;
                    $goods_pic->pic_url = $pic_url;
                    $goods_pic->is_delete = 0;
                    $goods_pic->save();
                }

                YyForm::updateAll(['is_delete' => 1], ['goods_id'=>$goods->id]);
                foreach ($this->form_list as $form) {
                    $form_list = new YyForm();
                    $form_list->goods_id = $goods->id;
                    $form_list->store_id = $this->store_id;
                    $form_list->name = $form['name'];
                    $form_list->type = $form['type'];
                    $form_list->required = $form['required'];
                    $form_list->default = $form['default'];
                    $form_list->tip = $form['tip'];
                    $form_list->sort = $form['sort'];
                    $form_list->is_delete = 0;
                    $form_list->addtime = time();
                    $form_list->save();
                }

                $this->setAttr($goods);
                //单商品分销设置
                $this->goods_share->store_id = $this->store_id;
                $this->goods_share->type = 1;
                $this->goods_share->goods_id = $goods->id;
                $this->goods_share->individual_share = $this->individual_share;
                $this->goods_share->share_commission_first = $this->share_commission_first;
                $this->goods_share->share_commission_second = $this->share_commission_second;
                $this->goods_share->share_commission_third = $this->share_commission_third;
                $this->goods_share->share_type = $this->share_type;
                $this->goods_share->rebate = $this->rebate;
                $this->goods_share->attr_setting_type = $this->attr_setting_type;
                $this->goods_share->save();


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
                    'num' => intval($this->stock) ? intval($this->stock) : 0,
                    'price' => floatval($this->price) ? floatval($this->price) : 0,
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
                'pic' => $item['pic'] ? $item['pic'] : '',
                'share_commission_first' => $item['share_commission_first'] ? $item['share_commission_first']: '',
                'share_commission_second' => $item['share_commission_second'] ? $item['share_commission_second']: '',
                'share_commission_third' => $item['share_commission_third'] ? $item['share_commission_third']: '',
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
