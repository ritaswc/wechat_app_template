<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/11
 * Time: 12:04
 */

namespace app\modules\mch\models;

use app\models\HomeNav;

/**
 * @property HomeNav $model
 */
class HomeNavEditForm extends MchModel
{
    public $model;
    public $store_id;
    public $name;
    public $sort;
    public $pic_url;
    public $url;
    public $open_type;
    public $is_hide;

    public function rules()
    {
        return [
            [['name', 'sort', 'pic_url', 'url', 'open_type'], 'trim'],
            [['name', 'pic_url', 'name',], 'required'],
            [['sort', 'is_hide'], 'integer','min'=>0,'max'=>999999],
            [['sort',], 'default', 'value' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'sort' => '排序',
            'pic_url' => '图标',
            'is_hide' => '是否隐藏',
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
            $this->model->store_id = $this->store_id;
        }
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
        return $this->getErrorResponse($this->model);
    }
}
