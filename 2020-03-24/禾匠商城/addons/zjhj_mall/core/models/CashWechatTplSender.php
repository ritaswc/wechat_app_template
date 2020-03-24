<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3
 * Time: 15:33
 */

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\common\CommonFormId;
use luweiss\wechat\Wechat;
use app\models\alipay\TplMsgForm;

/**
 * @property Store $store
 * @property Cash $cash
 * @property WechatTemplateMessage $wechat_template_message
 * @property User $user
 * @property FormId $form_id
 * @property Wechat $wechat
 * @property Share $share
 */
class CashWechatTplSender
{

    public $store_id;
    public $cash_id;

    public $store;
    public $cash;
    public $wechat_template_message;
    public $user;
    public $form_id;
    public $wechat;
    public $share;
    public $is_alipay;

    /**
     * @param integer $store_id
     * @param integer $order_id
     * @param Wechat $wechat
     * @param integer $type 0--提现 1--分销审核
     */
    public function __construct($store_id, $id, $wechat, $type = 0)
    {
        $this->store_id = $store_id;
        $this->wechat = $wechat;
        $this->store = Store::findOne($this->store_id);
        if ($type == 0) {
            $this->cash = Cash::findOne(['id' => $id]);
            if (!$this->cash) {
                return;
            }
            $this->user = User::findOne($this->cash->user_id);
            $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);
            if (empty($this->form_id)) {
                \Yii::warning('formId为空');
                return;
            }
        }
        if ($type == 1) {
            $this->share = Share::findOne(['id' => $id]);
            $this->user = User::findOne($this->share->user_id);
            $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);
            if (empty($this->form_id)) {
                \Yii::warning('formId为空');
                return;
            }
        }
        $this->is_alipay = $this->user->platform == 1;
        if ($this->is_alipay) {
            $tpl = TplMsgForm::get($this->store_id);
            $this->wechat_template_message = [
                'cash_success_tpl' => $tpl->cash_success_tpl,
                'cash_fail_tpl' => $tpl->cash_fail_tpl,
                'apply_tpl' => $tpl->apply_tpl,
            ];
        } else {
            $this->wechat_template_message = Option::getList('cash_success_tpl,cash_fail_tpl,apply_tpl', $this->store->id, 'share', '');
        }
    }

    /**
     * 发送提现到账模板消息
     */
    public function cashMsg()
    {
        try {
            if (!$this->wechat_template_message['cash_success_tpl']) {
                return;
            }
            $value = Cash::$type[$this->cash->type];
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message['cash_success_tpl'],
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/cash-detail/cash-detail',
                'data' => [
                    'keyword1' => [
                        'value' => $this->cash->price,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $value,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
                        'value' => '实时到账',
                        'color' => '#333333',
                    ],
                ],
            ];
            $this->sendTplMsg($data);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 发送提现失败模板消息
     */
    public function cashFailMsg()
    {
        try {
            if (!$this->wechat_template_message['cash_fail_tpl']) {
                return;
            }
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message['cash_fail_tpl'],
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/cash-detail/cash-detail',
                'data' => [
                    'keyword1' => [
                        'value' => $this->cash->price,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => '审核不通过',
                        'color' => '#333333',
                    ],
                ],
            ];
            $this->sendTplMsg($data);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 发送分销审核模板消息
     */
    public function applyMsg()
    {
        try {
            if (!$this->wechat_template_message['apply_tpl']) {
                return;
            }
            $status = $this->share->status == 1 ? "分销商审核通过" : "分销商审核不通过";
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message['apply_tpl'],
                'form_id' => $this->form_id->form_id,
                'page' => "pages/user/user",
                'data' => [
                    'keyword1' => [
                        'value' => $status,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => date('Y年m月d日 H:i', $this->user->time),
                        'color' => '#333333',
                    ],
                ],
            ];
            $this->sendTplMsg($data);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }


    private function sendTplMsg($data)
    {
        if (!$data['form_id']) {
            \Yii::warning('data[] form_id为空');
            return;
        }
        if ($this->is_alipay) {
            $config = MpConfig::get($this->store_id);
            $aop = $config->getClient();
            $request = AlipayRequestFactory::create('alipay.open.app.mini.templatemessage.send', [
                'biz_content' => [
                    'to_user_id' => $data['touser'],
                    'form_id' => $data['form_id'],
                    'user_template_id' => $data['template_id'],
                    'page' => $data['page'],
                    'data' => $data['data'],
                ],
            ]);
            $response = $aop->execute($request);

            if ($this->form_id && !empty($this->form_id)) {
                $this->form_id->send_count = $this->form_id->send_count + 1;
                $this->form_id->save();
            }

            if ($response->isSuccess() === false) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($response->getError(), JSON_UNESCAPED_UNICODE));
            }
        } else {
            $access_token = $this->wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->wechat->curl->post($api, $data);
            $res = json_decode($this->wechat->curl->response, true);

            if ($this->form_id && !empty($this->form_id)) {
                $this->form_id->send_count = $this->form_id->send_count + 1;
                $this->form_id->save();
            }

            if (!empty($res['errcode']) && $res['errcode'] != 0) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
            }
        }
    }
}
