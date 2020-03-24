<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/13
 * Time: 14:26
 */

namespace app\modules\mch\models;

use app\models\Attr;
use app\models\AttrGroup;

class AttrDeleteForm extends MchModel
{
    public $store_id;
    public $attr_id;

    public function rules()
    {
        return [
            [['attr_id',], 'trim'],
            [['attr_id',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'attr_name' => '规格名称',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $attr = Attr::find()->alias('a')->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')->where(['ag.store_id' => $this->store_id, 'a.id' => $this->attr_id])->one();
        if (!$attr) {
            return [
                'code' => 1,
                'msg' => '规格不存在',
            ];
        }
        $attr->is_delete = 1;
        if ($attr->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '删除失败',
            ];
        }
    }
}
