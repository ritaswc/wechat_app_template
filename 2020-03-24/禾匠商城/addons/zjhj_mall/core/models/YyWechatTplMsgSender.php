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

/**
 * @property Store $store
 * @property YyOrder $order
 * @property WechatTemplateMessage $wechat_template_message
 * @property User $user
 * @property FormId $form_id
 * @property Wechat $wechat
 */
class YyWechatTplMsgSender
{
    public $store_id;
    public $order_id;

    public $store;
    public $order;
    public $wechat_template_message;
    public $setting;
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
        $this->order = YyOrder::findOne($this->order_id);
        if (!$this->order) {
            return;
        }
        $this->user = User::findOne($this->order->user_id);

        $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);

        if (empty($this->form_id)) {
            \Yii::warning('form_id为空');
            return;
        }

        $this->is_alipay = $this->user->platform == 1;
        if ($this->is_alipay) {
            $tpl = TplMsgForm::get($this->store_id);
            $this->setting = (object)[
                'success_notice' => $tpl->yy_success_notice,
                'refund_notice' => $tpl->yy_refund_notice,
            ];
        } else {
            $this->setting = YySetting::findOne(['store_id' => $this->store->id]);
        }
    }

    /**
     * 发送支付通知模板消息
     */
    public function payMsg()
    {
        try {
//            if (!$this->setting->success_notice) {
//                return;
//            }
            $goods = YyGoods::find()
                ->select('name')
                ->andWhere(['id' => $this->order->goods_id])
                ->one();
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->setting->success_notice,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/book/order/order?status=1',
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
                        'value' => $goods['name'],
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
     * @param string $refund_reason 退款原因
     * @param string $remark 备注
     */
    public function refundMsg($refund_price, $refund_reason = '', $remark = '')
    {
        try {
//            if (!$this->setting->refund_notice) {
//                return;
//            }
            $goods = YyGoods::find()
                ->select('name')
                ->andWhere(['id' => $this->order->goods_id])
                ->one();
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->setting->refund_notice,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/order/order?status=4',
                'data' => [
                    'keyword1' => [
                        'value' => $this->order->order_no,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $goods->name,
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
