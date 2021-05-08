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
 * @property Order $order
 * @property WechatTemplateMessage $wechat_template_message
 * @property User $user
 * @property FormId $form_id
 * @property Wechat $wechat
 */
class WechatTplMsgSender
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
    public $form_id_alipay;
    public $pageUrl;

    /**
     * @param integer $store_id
     * @param integer $order_id
     * @param Wechat $wechat
     * @param array $otherData
     */
    public function __construct($store_id, $order_id, $wechat, $otherData = [])
    {
        $this->store_id = $store_id;
        $this->order_id = $order_id;
        $this->wechat = $wechat;
        $this->store = Store::findOne($this->store_id);

        // 砍价活动订单 TODO 砍价已至models/ActivityMsgTpl
        $type = $otherData['type'];
        if ($type === 'BARGAIN') {
            $this->order = BargainOrder::findOne($this->order_id);
            $this->pageUrl = 'bargain/order-list/order-list';
        } else {
            $this->order = Order::findOne($this->order_id);
        }

        if (!$this->order) {
            return;
        }
        $this->user = User::findOne($this->order->user_id);
        $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);

        if (empty($this->form_id)) {
            \Yii::warning('formId为空');
            return;
        }

        $this->form_id_alipay = FormId::find()->where(['order_no' => $this->order->order_no, 'type' => 'prepay_id'])
            ->andWhere(['<', 'send_count', 3])
            ->one();
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

            $goods_list = OrderDetail::find()
                ->select('g.name,od.num')
                ->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])->asArray()->all();
            $goods_names = '';
            foreach ($goods_list as $goods) {
                $goods_names .= $goods['name'];
            }
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->pay_tpl,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/order/order?status=1',
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
            $goods_list = OrderDetail::find()
                ->select('g.name,od.num')
                ->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])->asArray()->all();
            $goods_names = '';
            foreach ($goods_list as $goods) {
                $goods_names .= $goods['name'];
            }
            if ($this->is_alipay) {
                $form_id = $this->form_id_alipay->form_id;
            } else {
                $form_id = $this->form_id->form_id;
            }
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->revoke_tpl,
                'form_id' => $form_id,
                //'page' => 'pages/order/order?status=' . ($this->order->is_pay == 1 ? 1 : 0),
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
            $goods_list = OrderDetail::find()
                ->select('g.name,od.num')
                ->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])->asArray()->all();
            $goods_names = '';
            foreach ($goods_list as $goods) {
                $goods_names .= $goods['name'];
            }
            if ($this->is_alipay) {
                $form_id = $this->form_id_alipay->form_id;
            } else {
                $form_id = $this->form_id->form_id;
            }
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->send_tpl,
                'form_id' => $form_id,
                'page' => 'pages/order/order?status=2',
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
     * @param string $good_name 商品名称
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
                'page' => 'pages/order/order?status=4',
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

    /**
     * TODO 已废弃 需调用请用 models/ActivityMsgTpl.php
     * 发送参与活动成功模板消息
     * @param double $activity_name 活动名称
     * @param string $good_name 商品名称
     * @param string $remark 备注
     */
    public function activitySuccessMsg($activity_name, $good_name = '', $remark = '')
    {
        try {
            $activityTplMsg = Option::getList('success_tpl', $this->store->id, 'activity', '');

            if (!$activityTplMsg['success_tpl']) {
                return;
            }

            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $activityTplMsg['success_tpl'],
                'form_id' => $this->form_id->form_id,
                'emphasis_keyword' => 'keyword1.DATA',
                'page' => $this->pageUrl,
                'data' => [
                    'keyword1' => [
                        'value' => $activity_name,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $good_name,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
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
     * TODO 已废弃 需调用请用 models/ActivityMsgTpl.php
     * 发送参与活动失败模板消息
     * @param double $activity_name 活动名称
     * @param string $good_name 商品名称
     * @param string $remark 备注
     */
    public function activityRefundMsg($activity_name, $good_name = '', $remark = '')
    {
        try {
            $activityTplMsg = Option::getList('refund_tpl', $this->store->id, 'activity', '');

            if (!$activityTplMsg['refund_tpl']) {
                return;
            }

            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $activityTplMsg['refund_tpl'],
                'form_id' => $this->form_id->form_id,
                'emphasis_keyword' => 'keyword1.DATA',
                'page' => $this->pageUrl,
                'data' => [
                    'keyword1' => [
                        'value' => $activity_name,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $good_name,
                        'color' => '#333333',
                    ],
                    'keyword3' => [
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
            \Yii::warning('data[] form_id为空');
            return;
        }
        if ($this->is_alipay) {
            $config = MpConfig::get($this->store_id);
            $aop = $config->getClient();
            foreach ($data['data'] as &$value) {
                foreach ($value as &$item) {
                    $item = str_replace('', '\\', $item);
                    if ($item == '') {
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

            if ($this->form_id_alipay && !empty($this->form_id_alipay)) {
                $this->form_id_alipay->send_count = $this->form_id_alipay->send_count + 1;
                $this->form_id_alipay->save();
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
