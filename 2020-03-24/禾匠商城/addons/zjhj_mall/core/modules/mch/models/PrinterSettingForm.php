<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 16:43
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\PrinterSetting $model;
 */
class PrinterSettingForm extends MchModel
{
    public $store_id;
    public $model;

    public $printer_id;
    public $type;
    public $block_id;
    public $is_attr;
    public $big;


    public function rules()
    {
        return [
            [['printer_id','block_id','is_attr', 'big'],'integer'],
            [['type'],'default','value'=>(object)[]]
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->model->isNewRecord) {
            $this->model->is_delete = 0;
            $this->model->store_id = $this->store_id;
            $this->model->addtime = time();
        }
        $this->model->printer_id = $this->printer_id;
        $this->model->type = \Yii::$app->serializer->encode($this->type);
        $this->model->block_id = 0;
        $this->model->is_attr = $this->is_attr;
        $this->model->big = $this->big;
        if ($this->model->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
