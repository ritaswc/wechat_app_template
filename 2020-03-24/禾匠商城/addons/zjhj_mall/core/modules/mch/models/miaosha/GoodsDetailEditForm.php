<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\miaosha;


use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\MchModel;

class GoodsDetailEditForm extends MchModel
{
    public $is_level;
    public $individual_share;
    public $attr_setting_type;
    public $share_type;
    public $share_commission_first;
    public $share_commission_second;
    public $share_commission_third;
    public $attr;
    public $miaoshaGoods;
    public $goodsShare;
    public $msGoods;

    public function rules()
    {
        return [
            [['share_commission_first', 'share_commission_second', 'share_commission_third'], 'default', 'value' => 0],
            [['share_commission_first', 'share_commission_second', 'share_commission_third'], 'number', 'min' => 0, 'max' => 999999],
            [['attr'], 'app\models\common\admin\validator\AttrValidator'],
            [['is_level', 'individual_share', 'attr_setting_type', 'share_type'], 'integer']
        ];
    }

    public function save()
    {
        try {

            $t = \Yii::$app->db->beginTransaction();


            $this->setAttr($this->miaoshaGoods);

            $this->goodsShare->individual_share = $this->individual_share;
            $this->goodsShare->attr_setting_type = $this->attr_setting_type;
            $this->goodsShare->share_type = $this->share_type;
            $this->goodsShare->share_commission_first = $this->share_commission_first;
            $this->goodsShare->share_commission_second = $this->share_commission_second;
            $this->goodsShare->share_commission_third = $this->share_commission_third;
            $this->goodsShare->save();

            $t->commit();

            return [
                'code' => 0,
                'msg' => '保存成功'
            ];

        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => '保存失败'
            ];
        }
    }


    /**
     * @param Goods $goods
     */
    public function setAttr($miaoshaGoods)
    {
        $oldAttr = json_decode($miaoshaGoods->attr, true);

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        foreach ($oldAttr as $k => &$item) {
            $item['share_commission_first'] = $this->attr[$k]['share_commission_first'];
            $item['share_commission_second'] = $this->attr[$k]['share_commission_second'];
            $item['share_commission_third'] = $this->attr[$k]['share_commission_third'];

            foreach ($levelList as $level) {
                $keyName = 'member' . $level['level'];
                $item[$keyName] = $this->attr[$k][$keyName];
            }
        }

        $miaoshaGoods->attr = json_encode($oldAttr);
        $miaoshaGoods->is_level = $this->is_level;
        $miaoshaGoods->save();
    }
}