<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/6 19:07
 */


namespace app\modules\mch\models\wechatplatform;

use app\models\FormId;
use app\models\User;
use app\modules\mch\models\MchModel;

class SendMsgForm extends MchModel
{
    public $store_id;
    public $send_all;
    public $user_info;
    public $tpl;

    public function rules()
    {
        return [
            [['tpl'], 'required'],
            [['send_all'], 'safe'],
            [['tpl', 'user_info'], 'string'],
        ];
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $this->tpl = \Yii::$app->serializer->decode($this->tpl);
        if (is_array($this->tpl['maps'])) {
            foreach ($this->tpl['maps'] as $k => $map) {
                $keyName = 'keyword' . ($k + 1);
                $tpl[$keyName] = [
                    'value' => $map['value'],
                    'color' => $map['color'] ? $map['color'] : '#000000',
                ];
            }
        }

        $userInfo = \Yii::$app->serializer->decode($this->user_info);
        $titleStyle = '';
        if ($this->tpl['title_style'] == 1) {
            $titleStyle = 'keyword1.DATA';
        }
        $data = [
            'emphasis_keyword' => $titleStyle,
            'touser' => $userInfo['wechat_open_id'],
            'template_id' => $this->tpl['tpl_id'],
            'form_id' => $userInfo['form_id'],
            'page' => $this->tpl['miniprogram']['pagepath'],
            'data' => $tpl
        ];

        $res = $this->tplSend($data);

        return $res;
    }

    public function getUserList()
    {
        $list = User::find()->alias('u')->where([
            'AND',
            ['u.is_delete' => 0],
            ['u.type' => 1],
            ['u.store_id' => \Yii::$app->store->id]
        ])->joinWith(['formId' => function ($query) {
            return $query->alias('f')->andWhere([
                'f.send_count' => 0,
                'f.type' => 'form_id'
            ])->andWhere(['>', 'f.addtime', time() - (7 * 24 * 60 * 60)]);
        }])->select('u.id,u.wechat_open_id')->all();
        $user_info = [];
        foreach ($list as $item) {
            $newItem = [
                'wechat_open_id' => $item->wechat_open_id,
                'form_id' => $item->formId->form_id
            ];
            $user_info[] = $newItem;
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $user_info
            ]
        ];
    }

    public function tplSend($data)
    {
        $newData = $data;
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->wechat->curl->post($api, $data);
        $res = json_decode($this->wechat->curl->response, true);


        $form = FormId::find()->where(['form_id' => $newData['form_id']])->one();
        $form->send_count = $form->send_count + 1;
        $form->save();

        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
            return [
                'code' => 1,
                'msg' => "模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE),
            ];
        }

        return [
            'code' => 0,
            'msg' => '发送成功'
        ];
    }
}
