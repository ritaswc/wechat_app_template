<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 18:06
 */

namespace app\modules\mch\models\mch;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\MchCat;
use app\models\MchGoodsCat;
use app\models\MchPostageRules;
use app\models\Model;
use app\modules\mch\models\MchModel;

class GoodsDetailForm extends MchModel
{
    public $store_id;
    public $goods_id;

    public function search()
    {
        /* @var Goods $goods;*/
        $goods = Goods::find()->where(['id' => $this->goods_id, 'store_id' => $this->store_id])
            ->andWhere(['>', 'mch_id', 0])->one();

        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品错误，请刷新重试'
            ];
        }
        $cat_list = MchCat::find()->alias('c')->where(['c.mch_id' => $goods['mch_id']])
            ->leftJoin(['gc' => MchGoodsCat::tableName()], 'gc.cat_id=c.id')
            ->andWhere(['gc.goods_id' => $goods['id']])->asArray()->all();

        $goods_pic_list = GoodsPic::find()->where(['goods_id' => $goods['id'], 'is_delete' => Model::IS_DELETE_FALSE])->asArray()->select('pic_url')->column();

        if ($goods['freight'] == 0) {
            $postageRule = MchPostageRules::find()->where([
                'mch_id' => $goods['mch_id'],
                'is_enable' => 1
            ])->asArray()->one();
        } else {
            $postageRule = MchPostageRules::find()->where([
                'mch_id' => $goods['mch_id'],
                'id' => $goods['freight']
            ])->asArray()->one();
        }
        if (!$postageRule) {
            $postage = "无运费";
        } else {
            $postage = $postageRule['name'];
        }
        $attr_group_list = $goods->getAttrData();
        $attr_row_list = [];
        if ($goods->use_attr) {
            $attr_row_list = json_decode($goods->attr, true);
            foreach ($attr_row_list as $i => $attr_row) {
                foreach ($attr_row['attr_list'] as $j => $attr) {
                    $group = AttrGroup::find()->where(['id' => Attr::find()->select('attr_group_id')->where(['id' => $attr['attr_id']])])->one();
                    $attr_row_list[$i]['attr_list'][$j]['attr_group_name'] = $group ? $group->attr_group_name : '';
                }
            }
        }

        return [
            'cat_list' => $cat_list,
            'postage' => $postage,
            'goods_pic_list' => $goods_pic_list,
            'attr_group_list' => $attr_group_list,
            'attr_row_list' => $attr_row_list,
            'goods' => [
                'name' => $goods->name,
                'unit' => $goods->unit,
                'sort' => $goods->sort,
                'weight' => $goods->weight,
                'cover_pic' => $goods->cover_pic,
                'price' => $goods->price,
                'original_price' => $goods->original_price,
                'service' => $goods->service,
                'full_cut' => $goods->full_cut ? json_decode($goods->full_cut, true) : ['pieces' => null, 'forehead' => null],
                'use_attr' => $goods->use_attr ? 1 : 0,
                'detail' => $goods->detail,
                'goods_num' => $goods->getNum(),
            ],
        ];
    }
}
