<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12
 * Time: 11:58
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\MailSetting $list
 */
class MailForm extends MchModel
{
    public $store_id;
    public $list;
    public $send_mail;
    public $send_pwd;
    public $send_name;
    public $receive_mail;
    public $status;


    public function rules()
    {
        return [
            [['send_mail','send_pwd','send_name','receive_mail'],'required','on'=>'SUCCESS'],
            [['send_mail','send_pwd','send_name','receive_mail'],'trim'],
            [['send_mail','send_pwd','send_name','receive_mail'],'string'],
            [['status'],'in','range'=>[0,1]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->list->isNewRecord) {
            $this->list->is_delete = 0;
            $this->list->store_id = $this->store_id;
            $this->list->addtime = time();
        }
        $this->list->send_mail = $this->send_mail;
        $this->list->send_pwd = $this->send_pwd;
        $this->list->send_name = $this->send_name;
        $this->receive_mail = str_replace('，', ',', $this->receive_mail);
        $this->list->receive_mail = $this->receive_mail;
        $this->list->status = $this->status;
        if ($this->list->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->list);
        }
    }
}
