<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 16:15
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\SmsSetting $sms;
 */
class SmsForm extends MchModel
{
    public $sms;
    public $store_id;

    public $AccessKeyId;
    public $AccessKeySecret;
    public $name;
    public $sign;
    public $tpl;
    public $msg;
    public $status;
    public $mobile;
    public $refund;
    public $code;

    public function rules()
    {
        return [
            [['AccessKeyId','AccessKeySecret','sign','status','mobile'],'required','on'=>'SUCCESS'],
            [['AccessKeyId','AccessKeySecret','name','sign','tpl','status','mobile','msg','refund','code'],'trim'],
            [['msg',],'string'],
            [['status'],'in','range'=>[0,1]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'AccessKeyId' => '阿里云AccessKeyId',
            'AccessKeySecret' => '阿里云AccessKeySecret',
            'name' => '短信模板名称',
            'sign' => '短信模板签名',
            'tpl' => '短信模板ID',
            'msg' => '短信模板参数',
            'status' => '开启状态',
            'mobile' => '接受短信手机号',
        ];
    }
    public function save()
    {
        if ($this->validate()) {
            if ($this->sms->isNewRecord) {
                $this->sms->is_delete = 0;
                $this->sms->addtime = time();
                $this->sms->store_id = $this->store_id;
            }

            if(!($this->name || $this->refund['name'] || $this->code['name'])) {
                return [
                    'code' => 1,
                    'msg' => '短信模板名称不能为空'
                ];
            }
            $this->sms->attributes = $this->attributes;
            foreach ($this->refund as $k => $v) {
                $this->refund[$k] = trim($v);
            }
            $this->sms->tpl_refund = \Yii::$app->serializer->encode($this->refund);
            foreach ($this->code as $k => $v) {
                $this->code[$k] = trim($v);
            }
            $this->sms->tpl_code = \Yii::$app->serializer->encode($this->code);

            if ($this->sms->save()) {
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            } else {
                return [
                    'code'=>1,
                    'msg'=>'网络异常'
                ];
            }
        } else {
            return $this->errorResponse;
        }
    }
}
