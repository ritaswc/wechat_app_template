<?php
namespace app\modules\mch\models\lottery;
use app\models\LotterySetting;
use app\modules\mch\models\MchModel;

class LotterySettingForm extends MchModel
{
    public $store_id;
    public $model;
    public $rule;
    public $title;
    public $type;

    public function rules()
    {
        return [
            [['store_id', 'type'], 'integer'],
            [['rule'], 'string', 'max' => 2000],
            [['title'], 'string', 'max' => 255],
            [['rule'], 'required'],
        ];
    }

    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'rule' => '规则',
            'title' => '小程序标题',
            'type' => '是否强制',
        ]; 
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $this->model->rule = $this->rule;
        $this->model->title = $this->title;
        $this->model->store_id = $this->store_id;
        $this->model->type = $this->type;
        if($this->model->save()){
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}