<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/4/4
 * Time: 14:16
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\modules\mch\models\contact;


use app\hejiang\ApiCode;
use app\models\Option;
use app\models\TemplateMsg;
use app\modules\mch\models\MchModel;

class IndexForm extends MchModel
{
    public $contact_tpl;

    public function rules()
    {
        return [
            [['contact_tpl'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'contact_tpl' => '客服回复通知'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $templateMsg = TemplateMsg::findOne(['store_id' => $this->store->id, 'tpl_name' => 'contact_tpl']);
        if (!$templateMsg) {
            $templateMsg = new TemplateMsg();
            $templateMsg->store_id = $this->store->id;
            $templateMsg->tpl_name = 'contact_tpl';
        }

        $templateMsg->tpl_id = $this->contact_tpl;
        if (!$templateMsg->save()) {
            return $this->getErrorResponse($templateMsg);
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function search()
    {
        $templateMsg = TemplateMsg::findOne(['store_id' => $this->store->id, 'tpl_name' => 'contact_tpl']);
        $token = $this->getToken();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'contact_tpl' => $templateMsg ? $templateMsg->tpl_id : '',
                'url' => \Yii::$app->request->hostInfo .
                    \Yii::$app->urlManager->createUrl(['contact/callback', 'token' => $token, 'store_id' => $this->store->id])
            ]
        ];
    }

    public function resetToken()
    {
        $token = $this->setToken();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'url' => \Yii::$app->request->hostInfo .
                    \Yii::$app->urlManager->createUrl(['contact/callback', 'token' => $token, 'store_id' => $this->store->id])
            ]
        ];
    }

    private function getToken()
    {
        $token = Option::get('contact_token', $this->store->id, 'store', null);
        if (!$token) {
            $token = $this->setToken();
        }
        return $token;
    }

    private function setToken()
    {
        $token = \Yii::$app->security->generateRandomString();
        $res = Option::set('contact_token', $token, $this->store->id, 'store');
        return $token;
    }
}
