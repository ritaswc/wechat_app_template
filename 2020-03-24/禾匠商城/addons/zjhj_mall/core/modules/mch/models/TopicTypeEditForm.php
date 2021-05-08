<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 19:32
 */

namespace app\modules\mch\models;

use app\models\TopicType;

/**
 * @property Topic $model
 */
class TopicTypeEditForm extends MchModel
{
    public $model;

    public $store_id;
    public $name;
    public $sort;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'default', 'value' => 1000],
            [['name', 'sort'], 'required'],
            [['sort'], 'integer','min'=>0, 'max'=> 9999999],
            [['name'], 'string', 'max' => 255],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'sort' => '排序：升序',
            'name' => '名称'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->is_delete = 0;
        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
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
