<?php
namespace app\modules\mch\models;

use app\models\PondSetting;

/**
 * @property \app\models\Level $model;
 */
class PondSettingForm extends MchModel
{
    public $store_id;
    public $model;

    public $probability;
    public $oppty;
    public $type;
    public $start_time;
    public $end_time;
    public $title;
    public $rule;
    public $deplete_register;

    public function rules()
    {
        return [
            [['store_id', 'probability', 'oppty', 'type', 'start_time', 'end_time', 'deplete_register'], 'integer'],
            [['probability', 'oppty','type','start_time','end_time'], 'required'],
            [['probability', 'oppty', 'start_time', 'end_time', 'deplete_register'], 'integer','max'=>2000000000],
            [['title'], 'string', 'max' => 255],
            [['rule'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'probability' => '概率',
            'oppty' => '抽奖次数',
            'type' => '抽奖规则',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'title' => '小程序标题',
            'rule' => '规则说明',
            'deplete_register' => '消耗积分',
        ];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->model->store_id = $this->store_id;
        $this->model->probability = $this->probability;
        $this->model->oppty = $this->oppty;
        $this->model->deplete_register = $this->deplete_register;
        $this->model->type = $this->type;
        $this->model->rule = $this->rule;
        $this->model->title = $this->title;
        $this->model->start_time = $this->start_time;
        $this->model->end_time = $this->end_time;

        if ($this->model->save()) {
            return [
                'code'=>0,
                'msg'=>'保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
