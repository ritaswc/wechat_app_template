<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/4/4
 * Time: 14:53
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\models;


use app\hejiang\ApiCode;
use app\utils\WechatCreate;
use luweiss\wechat\Wechat;

/**
 * @property Wechat $wechat
 */
class ContactForm extends Model
{
    public $wechat;
    public $user;

    public $token;
    public $store_id;

    public $visiter_id;
    public $content;
    public $timestamp;
    public $avatar;
    public $service_url;
    public $service_name;
    public $state;


    public function rules()
    {
        return [
            [['token', 'visiter_id', 'store_id'], 'required'],
            [['token', 'visiter_id', 'content', 'timestamp', 'avatar', 'service_url', 'service_name', 'state'], 'string'],
            [['token', 'visiter_id', 'content', 'timestamp', 'avatar', 'service_url', 'service_name', 'state'], 'trim'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            if ($this->state != 'offline') {
                throw new \Exception('用户在线无需发送');
            }
            $token = Option::get('contact_token', $this->store_id, 'store', null);
            if (!$token) {
                throw new \Exception('无效的store_id');
            }
            if ($token != $this->token) {
                throw new \Exception('无效的token');
            }
            $this->user = User::findOne(['store_id' => $this->store_id, 'access_token' => $this->visiter_id]);
            if (!$this->user) {
                throw new \Exception('无效的用户');
            }
            $this->store = Store::findOne(['id' => $this->store_id]);
            $this->wechat = WechatCreate::create($this->store);
            $template = TemplateMsg::findOne(['store_id' => $this->store->id, 'tpl_name' => 'contact_tpl']);
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $template->tpl_id,
                'page' => 'pages/web/web?url=' . $this->service_url,
                'data' => [
                    'keyword1' => [
                        'value' => $this->service_name,
                        'color' => '#333333'
                    ],
                    'keyword2' => [
                        'value' => date('Y-m-d H:i:s', $this->timestamp),
                        'color' => '#333333'
                    ],
                    'keyword3' => [
                        'value' => "【消息内容】" . urldecode($this->content),
                        'color' => '#333333'
                    ],
                    'keyword4' => [
                        'value' => '您好，有商家客服联系您了，点击进入会话',
                        'color' => '#333333'
                    ],
                ]
            ];
            $this->tplSend($data);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '发送成功'
            ];

        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'data' => $exception
            ];
        }
    }

    private function tplSend($data)
    {
        $form = FormId::find()->where(['user_id' => $this->user->id])
            ->andWhere([
                'or',
                [ 'type' => 'form_id', 'send_count' => 0],
                [ 'type' => 'prepay_id', 'send_count' => [0, 1, 2]]
            ])
            ->andWhere(['>=', 'addtime', strtotime('-7 day')])
            ->orderBy(['addtime' => SORT_ASC])
            ->one();
        if (!$form) {
            throw new \Exception('没有form_id无法发送模板消息');
        }
        $data['form_id'] = $form->form_id;
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->wechat->curl->post($api, $data);
        $res = json_decode($this->wechat->curl->response, true);

        $form->send_count = $form->send_count + 1;
        $form->save();

        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new \Exception("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
        }

        return true;
    }
}
