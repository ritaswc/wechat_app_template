<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/5
 * Time: 17:37
 */

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\common\CommonFormId;
use luweiss\wechat\Wechat;
use app\models\alipay\TplMsgForm;

class PtNoticeSender
{
    public $wechat;
    public $store_id;
    public $order_id;
    public $order;
    public $user;
    public $form_id;
    public $wechat_template_message;

    /**
     * PtNoticeSender constructor.
     * @param Wechat $wechat
     * @param integer $store_id
     */
    public function __construct($wechat, $store_id)
    {
        $this->wechat = $wechat;
        $this->store_id = $store_id;

        $this->wechat_template_message = WechatTemplateMessage::findOne(['store_id' => $this->store_id]);
    }

    /**
     * 发送拼团成功模板消息
     * @param integer $order_id 拼团订单id（团长订单或团员订单）
     * @return bool
     */
    public function sendSuccessNotice($order_id)
    {
        $tpl_id = Option::get('pintuan_success_notice', $this->store_id);
        $tpl_id_alipay = TplMsgForm::get($this->store_id)->pt_success_notice;

        $order = PtOrder::find()->alias('po')
            ->select('po.*,u.wechat_open_id,u.nickname,pg.name AS goods_name')
            ->leftJoin(['u' => User::tableName()], 'po.user_id=u.id')
            ->leftJoin(['pod' => PtOrderDetail::find()->select('id,goods_id,order_id')->where([
                'AND',
                ['is_delete' => 0],
                ['IS NOT', 'id', null],
            ])->orderBy('addtime DESC')], 'po.id=pod.order_id')
            ->leftJoin(['pg' => PtGoods::tableName()], 'pod.goods_id=pg.id')
            ->where([
                'AND',
                [
                    'po.id' => $order_id,
                    'po.is_pay' => 1,
                    'po.is_delete' => 0,
                    'po.status' => 3,
                    'po.is_success' => 1,
                ],
            ])
            ->limit(1)
            ->asArray()
            ->one();
        if (!$order) {
            \Yii::warning('模板消息发送失败，订单不存在');
            return false;
        }
        if ($order['parent_id'] != 0) {
            $order = PtOrder::find()->alias('po')
                ->select('po.*,u.wechat_open_id,u.nickname,pg.name AS goods_name')
                ->leftJoin(['u' => User::tableName()], 'po.user_id=u.id')
                ->leftJoin(['pod' => PtOrderDetail::find()->select('id,goods_id,order_id')->where([
                    'AND',
                    ['is_delete' => 0],
                    ['IS NOT', 'id', null],
                ])->orderBy('addtime DESC')], 'po.id=pod.order_id')
                ->leftJoin(['pg' => PtGoods::tableName()], 'pod.goods_id=pg.id')
                ->where([
                    'AND',
                    [
                        'po.id' => $order['parent_id'],
                        'po.is_pay' => 1,
                        'po.is_delete' => 0,
                        'po.status' => 3,
                        'po.is_success' => 1,
                    ],
                ])
                ->limit(1)
                ->asArray()
                ->one();
            if (!$order) {
                \Yii::warning('模板消息发送失败，订单不存在');
                return false;
            }
        }
        $sub_order_list = PtOrder::find()->alias('po')
            ->select('po.*,pg.name AS goods_name')
            ->leftJoin(['pod' => PtOrderDetail::find()->select('id,goods_id,order_id')->where([
                'AND',
                ['is_delete' => 0],
                ['IS NOT', 'id', null],
            ])->orderBy('addtime DESC')], 'po.id=pod.order_id')
            ->leftJoin(['pg' => PtGoods::tableName()], 'pod.goods_id=pg.id')
            ->where([
                'AND',
                [
                    'po.parent_id' => $order['id'],
                    'po.is_pay' => 1,
                    'po.is_delete' => 0,
                    'po.status' => 3,
                    'po.is_success' => 1,
                ],
            ])
            ->orderBy('po.addtime')
            ->asArray()
            ->all();
        $order_list = array_merge([$order], $sub_order_list);
        $nickname_list = [];
        foreach ($order_list as $key => $order) {
            $wechatOpenId = User::find()
                ->andWhere(['id' => $order['user_id']])
                ->select('wechat_open_id')
                ->scalar();

            if ($order['order_no'] == "robot") {
                $order_list[$key]['nickname'] = PtRobot::find()
                    ->andWhere(['id' => $order['user_id']])
                    ->select('name')
                    ->scalar();
                $order_list[$key]['wechat_open_id'] = '';
            } else {
                $order_list[$key]['nickname'] = User::find()
                    ->andWhere(['id' => $order['user_id']])
                    ->select('nickname')
                    ->scalar();
                $order_list[$key]['wechat_open_id'] = $wechatOpenId;
            }
            $nickname_list[] = $order_list[$key]['nickname'];

            $formId = CommonFormId::getFormId($wechatOpenId);
            if (!empty($formId)) {
                $formId->send_count = $formId->send_count + 1;
                $formId->save();
            }
            $order_list[$key]['form_id'] = $formId->form_id;
        }

        foreach ($order_list as $order) {
            if (!$order['form_id']) {
                \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，form_id不存在");
                continue;
            }

            $platform = User::findOne(['id' => $order['user_id']])->platform;
            $is_alipay = $platform == 1;

            if ($is_alipay) {
                if (!$tpl_id_alipay) {
                    \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，支付宝模板消息未配置");
                }
            } else {
                if (!$tpl_id) {
                    \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，微信模板消息未配置");
                }
            }

            $data = [
                'touser' => $order['wechat_open_id'],
                'template_id' => $is_alipay ? $tpl_id_alipay : $tpl_id,
                'page' => 'pages/pt/order/order?status=2',
                'form_id' => $order['form_id'],
                'data' => [
                    'keyword1' => [
                        'value' => $order['goods_name'],
                        'color' => '#555555',
                    ],
                    'keyword2' => [
                        'value' => $order['order_no'],
                        'color' => '#555555',
                    ],
                    'keyword3' => [
                        'value' => implode(',', $nickname_list),
                        'color' => '#555555',
                    ],
                ],
            ];

            $this->sendTplMsg($data, $is_alipay);
        }
        return true;
    }

    /**
     * 发送拼团失败消息
     * @param integer $order_id 拼团订单id（团长订单）
     */
    public function sendFailNotice($order_id)
    {
        $tpl_id = Option::get('pintuan_fail_notice', $this->store_id);
        $tpl_id_alipay = TplMsgForm::get($this->store_id)->pt_fail_notice;

        $order = PtOrder::find()->alias('po')
            ->select('po.*,u.wechat_open_id,u.nickname,pg.name AS goods_name')
            ->leftJoin(['u' => User::tableName()], 'po.user_id=u.id')
            ->leftJoin(['pod' => PtOrderDetail::find()->select('id,goods_id,order_id')->where([
                'AND',
                ['is_delete' => 0],
                ['IS NOT', 'id', null],
            ])->orderBy('addtime DESC')], 'po.id=pod.order_id')
            ->leftJoin(['pg' => PtGoods::tableName()], 'pod.goods_id=pg.id')
            ->where([
                'AND',
                [
                    'po.id' => $order_id,
                    'po.is_pay' => 1,
                    'po.is_delete' => 0,
                    'po.status' => 4,
                    'po.parent_id' => 0,
                ],
            ])
            ->limit(1)
            ->asArray()
            ->one();
        if (!$order) {
            \Yii::warning('模板消息发送失败，订单不存在');
            return false;
        }
        $sub_order_list = PtOrder::find()->alias('po')
            ->select('po.*,u.wechat_open_id,u.nickname,pg.name AS goods_name')
            ->leftJoin(['u' => User::tableName()], 'po.user_id=u.id')
            ->leftJoin(['pod' => PtOrderDetail::find()->select('id,goods_id,order_id')->where([
                'AND',
                ['is_delete' => 0],
                ['IS NOT', 'id', null],
            ])->orderBy('addtime DESC')], 'po.id=pod.order_id')
            ->leftJoin(['pg' => PtGoods::tableName()], 'pod.goods_id=pg.id')
            ->where([
                'AND',
                [
                    'po.parent_id' => $order['id'],
                    'po.is_pay' => 1,
                    'po.is_delete' => 0,
                    'po.status' => 4,
                ],
            ])
            ->orderBy('po.addtime')
            ->asArray()
            ->all();
        $order_list = array_merge([$order], $sub_order_list);

        foreach ($order_list as $order) {
            $wechatOpenId = User::find()
                ->andWhere(['id' => $order['user_id']])
                ->select('wechat_open_id')
                ->scalar();

            $formId = CommonFormId::getFormId($wechatOpenId);

            if (!empty($formId)) {
                $formId->send_count = $formId->send_count + 1;
                $formId->save();
            }
            $order['form_id'] = $formId->form_id;

            if (!$order['form_id']) {
                \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，form_id不存在");
                continue;
            }

            $platform = User::findOne(['id' => $order['user_id']])->platform;
            $is_alipay = $platform == 1;

            if ($is_alipay) {
                if (!$tpl_id_alipay) {
                    \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，支付宝模板消息未配置");
                }
            } else {
                if (!$tpl_id) {
                    \Yii::warning("拼团订单(id={$order['id']})未发送模板消息，微信模板消息未配置");
                }
            }

            $data = [
                'touser' => $order['wechat_open_id'],
                'template_id' => $is_alipay ? $tpl_id_alipay : $tpl_id,
                'page' => 'pages/pt/order/order?status=3',
                'form_id' => $order['form_id'],
                'data' => [
                    'keyword1' => [
                        'value' => $order['goods_name'],
                        'color' => '#555555',
                    ],
                    'keyword2' => [
                        'value' => '未在规定时间内凑集拼团人数',
                        'color' => '#555555',
                    ],
                    'keyword3' => [
                        'value' => $order['order_no'],
                        'color' => '#555555',
                    ],
                ],
            ];

            $this->sendTplMsg($data, $is_alipay);
        }
        return true;
    }

    /**
     * 发送退款模板消息
     * @param double $refund_price 退款金额
     * @param string $good_name 退款原因
     * @param string $remark 备注
     */
    public function refundMsg($order_id = 0, $refund_price, $good_name = '', $remark = '')
    {
        $this->order = PtOrder::findOne($order_id);
        if (!$this->order) {
            return;
        }
        $user = User::findOne($this->order->user_id);
        $this->form_id = CommonFormId::getFormId($user->wechat_open_id);

        if (empty($this->form_id)) {
            \Yii::warning('formId为空');
            return;
        }

        // TODO 此处代码 $this->user 是空的
        $is_alipay = $this->user->platform == 1;
        if ($is_alipay) {
            $this->wechat_template_message = TplMsgForm::get($this->store_id);
        }

        try {
            if (!$this->wechat_template_message->refund_tpl) {
                return;
            }
            $data = [
                'touser' => $user->wechat_open_id,
                'template_id' => $this->wechat_template_message->refund_tpl,
                'form_id' => $this->form_id->form_id,
                'page' => 'pages/pt/order/order?status=4',
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
            $this->sendTplMsg($data, $is_alipay);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 发送订单取消模板消息
     */
    public function revokeMsg($remark = '订单已取消')
    {
        if (!$this->order) {
            return;
        }

        $user = User::find()->where(['id' => $this->order->user_id])->select('id,wechat_open_id')->one();

        $this->user = User::findOne($this->order->user_id);
        $this->form_id = CommonFormId::getFormId($user->wechat_open_id);
        if (empty($this->form_id)) {
            \Yii::warning('formId为空');
            return;
        }

        $is_alipay = $this->user->platform == 1;
        if ($is_alipay) {
            $this->wechat_template_message = TplMsgForm::get($this->store_id);
        }

        try {
            if (!$this->wechat_template_message->revoke_tpl) {
                return;
            }
            $goods_list = PtOrderDetail::find()
                ->select('g.name,od.num')
                ->alias('od')->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
                ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])->asArray()->all();
            $goods_names = '';
            foreach ($goods_list as $goods) {
                $goods_names .= $goods['name'];
            }
            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $this->wechat_template_message->revoke_tpl,
                'form_id' => $this->form_id->form_id,
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
            $this->sendTplMsg($data, $is_alipay);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    private function sendTplMsg($data, $is_alipay)
    {
        if ($is_alipay) {
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
            \Yii::error($res);

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
