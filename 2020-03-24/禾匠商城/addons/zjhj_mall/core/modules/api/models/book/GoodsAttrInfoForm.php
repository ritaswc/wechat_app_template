<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\api\models\book;


use app\hejiang\ApiResponse;
use app\models\common\CommonGoods;
use app\models\YyGoods;
use app\modules\api\models\ApiModel;

class GoodsAttrInfoForm extends ApiModel
{
    public $goods_id;
    public $attr_list;


    public function rules()
    {
        return [
            [['goods_id', 'attr_list'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->attr_list = json_decode($this->attr_list, true);
        $goods = YyGoods::findOne($this->goods_id);

        if (!$goods) {
            return new ApiResponse(1, '商品不存在');
        }

        $goodsData = [
            'attr' => $goods['attr'],
            'price' => $goods['price'],
            'is_level' => $goods['is_level']
        ];
        $res = CommonGoods::currentGoodsAttr($goodsData, $this->attr_list);

        return new ApiResponse(0, 'success', $res);
    }
}