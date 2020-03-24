<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/5
 * Time: 12:00
 */

namespace app\modules\mch\models\mch;

use app\models\MchCommonCat;
use app\modules\mch\models\MchModel;

class CommonCatEditForm extends MchModel
{
    /** @var  MchCommonCat $model */
    public $model;
    public $store_id;
    public $name;
    public $sort;

    public function rules()
    {
        return [
            [['name',], 'required',],
            [['sort',], 'integer', 'min' => 1,'max'=>99999999],
            [['sort',], 'default', 'value' => 1000,],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '类目名称',
            'sort' => '排序',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->attributes = $this->attributes;
        if ($this->model->isNewRecord) {
            $this->model->is_delete = 0;
        }
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
