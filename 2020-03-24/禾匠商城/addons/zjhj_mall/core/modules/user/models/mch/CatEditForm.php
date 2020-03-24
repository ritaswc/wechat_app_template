<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/12
 * Time: 11:17
 */

namespace app\modules\user\models\mch;

use app\models\MchCat;
use app\modules\user\models\UserModel;

class CatEditForm extends UserModel
{
    /** @var  MchCat $model */
    public $model;

    public $name;
    public $icon;
    public $parent_id;
    public $sort;

    public function rules()
    {
        return [
            [['name', 'icon',], 'trim'],
            [['name'], 'required'],
            [['parent_id', 'sort'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->attributes = $this->attributes;
        if ($this->model->isNewRecord) {
            $this->model->addtime = time();
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
