<?php
namespace app\modules\mch\models\step;

use app\models\StepSetting;
use app\modules\mch\models\MchModel;
use app\models\Pic;
use app\models\Option;

class StepSettingForm extends MchModel
{
    public $store_id;
    public $model;
    public $rule;
    public $title;
    public $convert_max;
    public $convert_ratio;
    public $invite_ratio;
    public $pic_list;
    public $remind_time;
    public $activity_rule;
    public $currency_name;
    public $ranking_pic;
    public $activity_pic;
    public $share_title;
    public $qrcode_title;
    public $ranking_num;

    public function rules()
    {
        return [
            [['store_id', 'convert_max', 'convert_ratio', 'invite_ratio'], 'integer'],
            [['rule', 'activity_rule', 'share_title'], 'string', 'max' => 2000],
            [['qrcode_title'], 'string', 'min' => 0, 'max' => 12],
            [['title', 'remind_time', 'currency_name', 'ranking_pic', 'activity_pic'], 'string', 'max' => 255],
            [['invite_ratio'], 'integer','min' => 0, 'max' => 1000],
            [['convert_ratio'], 'integer','min' => 1, 'max' => 999999999],
            [['convert_max', 'ranking_num'], 'integer','min' => 0, 'max' => 999999999],
            [['pic_list'], 'trim'],
            [['convert_ratio', 'remind_time',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'rule' => '规则',
            'title' => '小程序标题',
            'convert_max' => '最高限制',
            'convert_ratio' => '兑换比率',
            'invite_ratio' => '邀请比率',
            'activity_rule' => '活动规则',
            'currency_name' => '活力币别名',
            'remind_time' => '提现时间',
            'share_title' => '转发标题',
            'ranking_num' => '全国排行限制',
            'qrcode_title' => '海报文字',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };

        $step['currency_name'] = $this->currency_name;
        $step['ranking_pic'] = $this->ranking_pic;
        $step['activity_pic'] = $this->activity_pic;
        Option::set('step', $step, $this->store->id, 'admin');

        Pic::updateAll(['is_delete' => 1], ['type' => 1, 'store_id' => $this->store_id]);
        foreach ($this->pic_list as $pic_url) {
            $pic = new Pic();
            $pic->type = 1;
            $pic->store_id = $this->store_id;
            $pic->pic_url = $pic_url;
            $pic->is_delete = 0;
            $pic->save();
        }
        $this->model->attributes = $this->attributes;
        $this->model->remind_time = $this->remind_time;
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
