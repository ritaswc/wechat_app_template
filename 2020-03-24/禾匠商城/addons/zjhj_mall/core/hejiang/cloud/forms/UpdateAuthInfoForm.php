<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/20 17:49
 */


namespace app\hejiang\cloud\forms;


use app\hejiang\cloud\Cloud;
use app\models\Model;
use app\validators\DomainValidator;

class UpdateAuthInfoForm extends Model
{
    public $domain;

    public function rules()
    {
        return [
            [['domain'], 'trim'],
            [['domain'], 'required'],
            [['domain'], DomainValidator::className(),],
        ];
    }

    public function attributeLabels()
    {
        return [
            'domain' => '域名',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        if (Cloud::setLocalAuthInfo($this->attributes)) {
            return [
                'code' => 0,
                'msg' => '保存成功。',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '保存失败。',
            ];
        }
    }
}
