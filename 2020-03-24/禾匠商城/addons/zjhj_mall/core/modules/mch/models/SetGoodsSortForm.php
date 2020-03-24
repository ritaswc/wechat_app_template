<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/6/8
 * Time: 9:47
 */


namespace app\modules\mch\models;

use app\models\Goods;

class SetGoodsSortForm extends MchModel
{
    public $store_id;
    public $goods_id;
    public $sort;

    public function rules()
    {
        return [
            [['goods_id', 'sort'], 'required'],
            [['goods_id',], 'integer'],
            [['sort'], 'integer', 'min' => 0, 'max' => 100000000],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->validationError;
        }
        $goods = Goods::findOne([
            'store_id' => $this->store_id,
            'id' => $this->goods_id,
        ]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在。',
            ];
        }
        $goods->sort = $this->sort;
        $goods->save();
        return [
            'code' => 0,
            'msg' => '保存成功。',
        ];
    }
}
