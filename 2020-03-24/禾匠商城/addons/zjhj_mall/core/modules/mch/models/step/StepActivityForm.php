<?php
namespace app\modules\mch\models\step;

use app\models\StepActivity;
use app\modules\mch\models\MchModel;
use app\utils\TaskCreate;

class StepActivityForm extends MchModel
{
    public $store_id;
    public $model;

    public $bail_currency;
    public $step_num;
    public $name;
    public $open_date;
    public $status;

    public $currency;

    public function rules()
    {
        return [
            [['store_id', 'status'], 'integer'],
            [['open_date', 'name', 'step_num', 'bail_currency', 'status'], 'required'],
            [['step_num'], 'integer', 'min' => 1, 'max' => 999999999],
            [['bail_currency', 'currency'], 'number', 'min' => 0.01, 'max' => 999999999],
            [['open_date'], 'safe'],
            [['currency'],'default','value' => 0],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => '活动标题',
            'currency' => '奖金池',
            'bail_currency' => '缴纳金',
            'step_num' => '挑战布数',
            'open_date' => '开放日期',
            'type' => '0进行中 1 已完成',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };

        if ($this->model->open_date != $this->open_date) {
            $data = StepActivity::findOne([
                'store_id' => $this->store_id,
                'open_date' => $this->open_date,
                'is_delete' => 0
            ]);
            if ($data) {
                return [
                    'code' => 1,
                    'msg' => '此天活动已存在'
                ];
            }
        }
        $this->model->attributes = $this->attributes;
        $this->model->create_time = time();
        if ($this->model->save()) {
            //TaskCreate::stepActivity($this->model->id);
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
