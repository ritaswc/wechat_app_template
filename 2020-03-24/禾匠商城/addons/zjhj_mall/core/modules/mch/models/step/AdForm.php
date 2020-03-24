<?php
namespace app\modules\mch\models\step;

use app\models\Ad;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class AdForm extends MchModel
{
    public $store_id;
    public $model;

    public $status;
    public $unit_id;
    public $type;

    public function rules()
    {
        return [
            [['store_id', 'status', 'type'], 'integer'],
            [['status', 'unit_id','type'], 'required'],
            [['unit_id'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()  
    { 
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'is_delete' => 'Is Delete',
            'status' => '0关闭 1开启',
            'unit_id' => '广告id',
            'create_time' => 'Create Time',
        ]; 
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if($this->model->type != $this->type){
            $data = Ad::findOne([
                'store_id' => $this->store_id,
                'type' => $this->type,
                'is_delete' => 0
            ]);
            if($data) {
                return [
                    'code' => 1,
                    'msg' => '数据已存在'
                ];
            }
        }

        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
        $this->model->create_time = time();
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}