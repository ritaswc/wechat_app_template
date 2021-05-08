<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\alipay\TplMsgForm;
use app\models\common\CommonFormId;
use app\utils\WechatCreate;

class ActivityMsgTpl
{
    public $store;
    public $wechat_template_message;
    public $user;
    public $form_id;
    public $wechat;
    public $is_alipay;
    public $pageUrl;

    public function __construct($userId, $type)
    {
        if (!$userId) {
            return;
        }

        $storeId = \Yii::$app->store->id;
        $wechat = null;
        if (isset(\Yii::$app->controller->wechat)) {
            $wechat = \Yii::$app->controller->wechat;
        } else {
            $wechat = WechatCreate::create(\Yii::$app->store);
        }
        $this->wechat = $wechat;
        $this->store = Store::findOne($storeId);

        $this->pageUrl = '';
        // 发放优惠券
        if ($type === 'COUPON') {
            $this->pageUrl = 'pages/coupon/coupon';
        }

        // 分销佣金
        if ($type === 'SHARE') {
            $this->pageUrl = 'pages/share-order/share-order';
        }

        // 商城订单核销
        if ($type === 'ORDER_CLERK') {
            $this->pageUrl = 'pages/order/order?status=3';
        }

        // 预约核销
        if ($type === 'BOOK_CLERK') {
            $this->pageUrl = 'pages/book/order/order?status=2';
        }

        // 砍价活动
        if ($type === 'BARGAIN') {
            $this->pageUrl = 'bargain/order-list/order-list';
        }

        if ($type === 'STEP') {
            $this->pageUrl = 'step/index/index';
        }

        if ($type === 'LOTTERY') {
            $this->pageUrl = 'lottery/prize/prize';
        }

        // 除了步数宝插件，其它插件如果form_id为空 则不发送模板消息
        if ($type === 'STEP' || $type === 'LOTTERY') {
            return;
        }

        $this->user = User::findOne($userId);
        $this->form_id = CommonFormId::getFormId($this->user->wechat_open_id);
        if (empty($this->form_id)) {
            \Yii::warning('FormId为空');
            return;
        }
        $this->is_alipay = $this->user->platform == 1;


        if ($this->is_alipay) {
            $this->wechat_template_message = TplMsgForm::get($this->store->id);
        } else {
            $this->wechat_template_message = WechatTemplateMessage::findOne(['store_id' => $this->store->id]);
        }
    }


    /**
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

    /**
     * 账户变动提醒
     * @param string $changeCause 变动原因
     * @param string $remark
     */
    public function accountChangeMsg($changeCause = '', $remark = '')
    {
        try {
            $accountTplMsg = FxhbSetting::find()->where(['store_id' => $this->store->id])->one();

            if (!$accountTplMsg['tpl_msg_id']) {
                return;
            }

            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $accountTplMsg['tpl_msg_id'],
                'form_id' => $this->form_id->form_id,
                'page' => $this->pageUrl,
                'data' => [
                    'keyword1' => [
                        'value' => $remark,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $changeCause,
                        'color' => '#333333',
                    ]
                ],
            ];

            $this->sendTplMsg($data);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }


    /**
     * 订单核销模板消息
     * @param $orderNo
     * @param $remark
     */
    public function orderClerkTplMsg($orderNo, $remark)
    {
        try {
            $mchTplMsg = Option::get('mch_tpl_msg', $this->store->id, ['apply' => '', 'order' => '']);


            if (!$mchTplMsg['order']) {
                return;
            }

            $data = [
                'touser' => $this->user->wechat_open_id,
                'template_id' => $mchTplMsg['order'],
                'form_id' => $this->form_id->form_id,
                'page' => $this->pageUrl,
                'data' => [
                    'keyword1' => [
                        'value' => $orderNo,
                        'color' => '#333333',
                    ],
                    'keyword2' => [
                        'value' => $remark,
                        'color' => '#333333',
                    ]
                ],
            ];

            $this->sendTplMsg($data);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 发送提醒模板消息
     * @param double $refund_price
     */
    public function sendRemindcNotice($ids)
    {
        try {
            $activityTplMsg = Option::getList('success_tpl', $this->store->id, 'activity', '');

            if (!$activityTplMsg['success_tpl']) {
                return;
            }

            $list = User::find()->where(['in', 'id', $ids])->asArray()->all();
            if (!$list) {
                \Yii::warning('模板消息发送失败，用户不存在');
                return false;
            }

            foreach ($list as $item) {
                $this->form_id = CommonFormId::getFormId($item['wechat_open_id']);
                if (empty($this->form_id)) {
                    \Yii::warning('FormId为空');
                    continue;
                }

                $data = [
                    'touser' => $item['wechat_open_id'],
                    'template_id' => $activityTplMsg['success_tpl'],
                    'emphasis_keyword' => 'keyword1.DATA',
                    'page' => $this->pageUrl,
                    'form_id' => $this->form_id->form_id,
                    'data' => [
                        'keyword1' => [
                            'value' => '步数兑换',
                            'color' => '#555555',
                        ],
                        'keyword2' => [
                            'value' => '步数每日兑换提醒',
                            'color' => '#555555',
                        ],
                        'keyword3' => [
                            'value' => '步数零点清空哦',
                            'color' => '#555555',
                        ],
                    ],
                ];

                $this->sendTplMsg($data);
                $remind = new StepRemind();
                $remind->store_id = $this->store->id;
                $remind->user_id = $item['id'];
                $remind->date = date('Y-m-d', time());
                $remind->save();
            };
            return true;
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 步数挑战成功通知
     * @param  [type] $ids  [description]
     * @param  [type] $info [description]
     * @return [type]       [description]
     */
    public function sendSuccessNotice($ids, $info)
    {
        try {
            $activityTplMsg = Option::getList('success_tpl', $this->store->id, 'activity', '');

            if (!$activityTplMsg['success_tpl']) {
                return;
            }


            $list = User::find()->where(['in', 'id', $ids])->asArray()->all();
            if (!$list) {
                \Yii::warning('模板消息发送失败，用户不存在');
                return false;
            }

            foreach ($list as $item) {
                $this->form_id = CommonFormId::getFormId($item['wechat_open_id']);

                if (empty($this->form_id)) {
                    \Yii::warning('FormId为空');
                    continue;
                }

                $data = [
                    'touser' => $item['wechat_open_id'],
                    'template_id' => $activityTplMsg['success_tpl'],
                    'page' => $this->pageUrl,
                    'form_id' => $this->form_id->form_id,
                    'data' => [
                        'keyword1' => [
                            'value' => '步数挑战',
                            'color' => '#555555',
                        ],
                        'keyword2' => [
                            'value' => $info['name'],
                            'color' => '#555555',
                        ],
                        'keyword3' => [
                            'value' => '已获得' . $info['currency_num'] . '活力币',
                            'color' => '#555555',
                        ],
                    ],
                ];
                $this->sendTplMsg($data);
            };
            return true;
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 步数挑战失败通知
     * @param  [type] $ids  [description]
     * @param  [type] $info [description]
     * @return [type]       [description]
     */
    public function sendErrorNotice($ids, $info, $type = 'success')
    {
        try {
            $activityTplMsg = Option::getList('refund_tpl', $this->store->id, 'activity', '');

            if (!$activityTplMsg['refund_tpl']) {
                return;
            }

            $title = $type == 'disband' ? '(活动被解散)' : '';
            $list = User::find()->where(['in','id',$ids])->asArray()->all();

            if (!$list) {
                \Yii::warning('模板消息发送失败，用户不存在');
                return false;
            }

            foreach ($list as $item) {
                $this->form_id = CommonFormId::getFormId($item['wechat_open_id']);

                if (empty($this->form_id)) {
                    \Yii::warning('FormId为空');
                    continue;
                }

                $data = [
                    'touser' => $item['wechat_open_id'],
                    'template_id' => $activityTplMsg['refund_tpl'],
                    'page' => $this->pageUrl,
                    'form_id' => $this->form_id->form_id,
                    'data' => [
                        'keyword1' => [
                            'value' => '步数挑战' . $title,
                            'color' => '#555555',
                        ],
                        'keyword2' => [
                            'value' => $info['name'],
                            'color' => '#555555',
                        ],
                        'keyword3' => [
                            'value' => '很抱歉，本次未达标',
                            'color' => '#555555',
                        ],
                    ],
                ];
                $this->sendTplMsg($data);
            };
            return true;
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }


    public function sendLotterySuccNotice($ids, $info)
    {
        $tpl_id = Option::get('lottery_success_notice', $this->store->id, 'lottery', '');
        if (!$tpl_id) {
            return;
        }

        $list = User::find()->where(['in', 'id', $ids])->asArray()->all();
        if (!$list) {
            \Yii::warning('模板消息发送失败，订单不存在');
            return false;
        }
        foreach ($list as $item) {
            $this->form_id = CommonFormId::getFormId($item['wechat_open_id']);

            if (empty($this->form_id)) {
                \Yii::warning('FormId为空');
                continue;
            }

            $data = [
                'touser' => $item['wechat_open_id'],
                'template_id' => $tpl_id,
                'page' => $this->pageUrl . '?status=2',
                'form_id' => $this->form_id->form_id,
                'data' => [
                    'keyword1' => [
                        'value' => '恭喜中奖',
                        'color' => '#555555',
                    ],
                    'keyword2' => [
                        'value' => $info['name'],
                        'color' => '#555555',
                    ],
                ],
            ];
            $this->sendTplMsg($data);
        }
        return true;
    }

    public function sendLotteryErrNotice($ids, $info)
    {
        $activityTplMsg = Option::getList('refund_tpl', $this->store->id, 'activity', '');

        if (!$activityTplMsg['refund_tpl']) {
            return;
        }
        $list = User::find()->where(['in', 'id', $ids])->asArray()->all();
        if (!$list) {
            \Yii::warning('模板消息发送失败，用户不存在');
            return false;
        }

        foreach ($list as $item) {
            $this->form_id = CommonFormId::getFormId($item['wechat_open_id']);
            if (empty($this->form_id)) {
                \Yii::warning('FormId为空');
                continue;
            }

            $data = [
                'touser' => $item['wechat_open_id'],
                'template_id' => $activityTplMsg['refund_tpl'],
                'page' => $this->pageUrl . '?status=1',
                'form_id' => $this->form_id->form_id,
                'data' => [
                    'keyword1' => [
                        'value' => '幸运抽奖',
                        'color' => '#555555',
                    ],
                    'keyword2' => [
                        'value' => $info['name'],
                        'color' => '#555555',
                    ],
                    'keyword3' => [
                        'value' => '很抱歉，本次未中奖',
                        'color' => '#555555',
                    ],
                ],
            ];
            $this->sendTplMsg($data);
        }
        return true;
    }

    private function sendTplMsg($data)
    {
        // 如果form_id为空 则不发送模板消息
        if (!$data['form_id']) {
            \Yii::warning('FormId为空');
            return;
        }

        if ($this->is_alipay) {
            $config = MpConfig::get($this->store->id);
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
