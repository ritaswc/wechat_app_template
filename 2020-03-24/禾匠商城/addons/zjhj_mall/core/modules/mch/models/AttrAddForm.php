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

class AttrAddForm extends MchModel
{
    public $store_id;
    public $attr_group_name;
    public $attr_name;

    public function rules()
    {
        return [
            [['attr_group_name', 'attr_name',], 'trim'],
            [['attr_group_name', 'attr_name',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'attr_group_name' => '规格分类',
            'attr_name' => '规格名称',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $attrGroup = AttrGroup::findOne([
            'attr_group_name' => $this->attr_group_name,
            'store_id' => $this->store_id,
            'is_delete' => 0,
        ]);
        if (!$attrGroup) {
            $attrGroup = new AttrGroup();
            $attrGroup->store_id = $this->store_id;
            $attrGroup->attr_group_name = $this->attr_group_name;
            $attrGroup->is_delete = 0;
            if (!$attrGroup->save()) {
                return $this->getErrorResponse($attrGroup);
            }
        }
        $attr = Attr::findOne([
            'attr_group_id' => $attrGroup->id,
            'attr_name' => $this->attr_name,
            'is_delete' => 0,
        ]);
        if ($attr) {
            return [
                'code' => 0,
                'msg' => '规格已经存在',
            ];
        }
        $attr = new Attr();
        $attr->attr_group_id = $attrGroup->id;
        $attr->attr_name = $this->attr_name;
        $attr->is_delete = 0;
        if (!$attr->save()) {
            return $this->getErrorResponse($attr);
        }
        return [
            'code' => 0,
            'msg' => '规格添加成功',
        ];
    }
}
