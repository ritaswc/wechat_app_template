<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/21
 * Time: 11:53
 */

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\common\CommonFormId;
use luweiss\wechat\Wechat;
use app\models\alipay\TplMsgForm;
use yii\base\Theme;

/**
 * @property Store $store
 * @property Order $order
 * @property WechatTemplateMessage $wechat_template_message
 * @property User $user
 * @property FormId $form_id
 * @property Wechat $wechat
 */
class MsWechatTplMsgSender
{
    public $store_id;
    public $order_id;

    public $store;
    public $order;
    public $wechat_template_message;
    public $user;
    public $form_id;
    public $wechat;
    public $is_alipay;

    /**
     * @param integer $store_id
     * @param integer $order_id
     * @param Wechat $wechat
     */
    public function __construct($store_id, $order_id, $wechat)
    {
        $this->store_id = $store_id;
        $this->order_id = $order_id;
        $this->wechat = $wechat;
        $this->store = Store::findOne($this->store_id);
        $this->order = MsOrder::findOne($this->order_id);
        if (!$this->order) {
            return;
        }
        $this->user = User::findOne($this->order->user_id);
        $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);

        if (!$this->form_id && empty($this->form_id)) {
            \Yii::warning('form_id为空');
            return;
        }

        $this->is_alipay = $this->user->platform == 1;
        if ($this->is_alipay) {
            $this->wechat_template_message = TplMsgForm::get($this->store_id);
        } else {
            $this->wechat_template_message = WechatTemplateMessage::findOne(['store_id' => $this->store->id]);
        }
    }

    /**
     * 发送支付通知模板消息
     */
    public function payMsg()
    {
        try {
            if (!$this->wechat_template_message->pay_tpl) {
                return;
            }
            $goods_names = MsGoods::find()->andWhere(['id' => $this->order->goods_id])->select('name')->scalar();
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->pay_tpl,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/miaosha/order/order?status=1',
                'data' => [
                    'keyword1' => [
                        'value' => $this->order->order_no,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => date('Y-m-d H:i:s', $this->order->pay_time),
                        'color' => '#333333',
                    ],
                    'keyword3' => [
                        'value' => $this->order->pay_price,
                        'color' => '#333333',
                    ],
                    'keyword4' => [
                        'value' => $goods_names,
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
     * 发送订单取消模板消息
     */
    public function revokeMsg($remark = '订单已取消')
    {
        try {
            if (!$this->wechat_template_message->revoke_tpl) {
                return;
            }
            $goods_names = MsGoods::find()->andWhere(['id' => $this->order->goods_id])->select('name')->scalar();
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->revoke_tpl,
                'form_id' => $this->form_id->form_id,
                'data' => [
                    'keyword1' => [
                        'value' => $goods_names,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $this->order->order_no,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
                        'value' => $this->order->total_price,
                        'color' => '#333333',
                    ],
                    'keyword4' => [
                        'value' => $remark,
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
     * 发送发货模板消息
     */
    public function sendMsg()
    {
        try {
            if (!$this->wechat_template_message->send_tpl) {
                return;
            }
            $goods_names = MsGoods::find()->andWhere(['id' => $this->order->goods_id])->select('name')->scalar();
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->send_tpl,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/miaosha/order/order?status=2',
                'data' => [
                    'keyword1' => [
                        'value' => $goods_names,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $this->order->express,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
                        'value' => $this->order->express_no,
                        'color' => '#333333',
                    ],
                    'keyword4' => [
                        'value' => $this->order->words,
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
     * 发送退款模板消息
     * @param double $refund_price 退款金额
     * @param string $good_name 退款原因
     * @param string $remark 备注
     */
    public function refundMsg($refund_price, $good_name = '', $remark = '')
    {
        try {
            if (!$this->wechat_template_message->refund_tpl) {
                return;
            }

            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->refund_tpl,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/miaosha/order/order?status=4',
                'data' => [
                    'keyword1' => [
                        'value' => $this->order->order_no,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $good_name,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
                        'value' => $refund_price,
                        'color' => '#333333',
                    ],
                    'keyword4' => [
                        'value' => $remark,
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
            \Yii::warning('data[]form_id为空');
            return;
        }
        if ($this->is_alipay) {
            $config = MpConfig::get($this->store_id);
            $aop = $config->getClient();
            foreach($data['data'] as &$value){
                foreach($value as &$item){
                    $item = str_replace('','\\',$item);
                    if($item == ''){
                        $item = '-';
                    }
                }
                unset($item);
            }
            unset($value);
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
