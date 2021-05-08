<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 11:05
 */

namespace app\modules\mch\models;

use app\models\Printer;

/**
 * @property Printer $model
 */
class PrinterForm extends MchModel
{
    public $model;
    public $store_id;


    public $name;
    public $printer_type;
    public $printer_setting;

    public function rules()
    {
        return [
            [['name','printer_type'],'string'],
            [['name','printer_type'],'trim'],
            [['printer_setting'],'default','value'=>(object)[]]
        ];
    }

    public function save()
    {
        if ($this->model->isNewRecord) {
            $this->model->store_id = $this->store_id;
            $this->model->is_delete = 0;
            $this->model->addtime = time();
        }
        $this->model->name = $this->name;
        $this->model->printer_type = $this->printer_type;
        foreach ($this->printer_setting as $k => $v) {
            $this->printer_setting[$k] = trim($v);
        }
        $this->model->printer_setting = \Yii::$app->serializer->encode($this->printer_setting);
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
