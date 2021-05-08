<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/1
 * Time: 15:01
 */

namespace app\modules\mch\models;

use app\models\HomeBlock;
use app\models\Store;

/**
 * @property HomeBlock $model
 * @property Store $store
 */
class HomeBlockEditForm extends MchModel
{
    public $model;
    public $store;

    public $name;
    public $pic_list;
    public $style;

    public function rules()
    {
        return [
            [['name',], 'trim'],
            [['name', 'pic_list','style'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '板块名称',
            'pic_list' => '板块图片',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->name = $this->name;
        $this->model->style = $this->style;
        $this->model->data = \Yii::$app->serializer->encode([
            'pic_list' => $this->pic_list,
        ]);
        if ($this->model->isNewRecord) {
            $this->model->store_id = $this->store->id;
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
