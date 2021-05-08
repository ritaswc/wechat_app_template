<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23
 * Time: 14:26
 */

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\common\api\CommonOrder;
use app\models\common\api\CommonShoppingList;
use app\models\common\CommonFormId;
use app\models\tplmsg\AdminTplMsgSender;
use app\modules\api\controllers\ShareController;
use app\modules\api\models\BindForm;
use app\modules\api\models\CouponPaySendForm;
use app\modules\api\models\ShareForm;
use app\modules\api\models\ShareMoneyForm;
use app\utils\PinterOrder;
use app\utils\SendMail;
use app\utils\Sms;
use luweiss\wechat\Wechat;
use app\models\alipay\TplMsgForm;

class OrderWarn extends Model
{
    public $store_id;
    public $order_id;

    public $order_type; //订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单
    public $order;
    public $wechat;
    public $form_id;
    public $order_refund_no;

    /**
     * @return Wechat
     */
    public function getWechat()
    {
        return isset(\Yii::$app->controller->wechat) ? \Yii::$app->controller->wechat : null;
    }

    //支付完成之后，相关的操作
    public function notify()
    {
        if (\Yii::$app->fromWechatApp()) {
            $this->wechat = $this->getWechat();
        }
        try {
            $is_sms = 0;
            $is_mail = 0;
            $is_print = 0;
            switch ($this->order_type) {
                case 0:
                    $this->OrderNotify();
                    if ($this->order->type == 0) {
                        $setting = (object)[
                            'is_sms' => 1,
                            'is_mail' => 1,
                            'is_print' => 1
                        ];
                    } else {
                        $setting = BargainSetting::findOne(['store_id' => $this->store_id]);
                    }
                    break;
                case 1:
                    $this->MsOrderNotify();
                    $setting = MsSetting::findOne(['store_id' => $this->store_id]);
                    break;
                case 2:
                    $this->PtOrderNotify();
                    $setting = PtSetting::findOne(['store_id' => $this->store_id]);
                    break;
                case 3:
                    $this->YyOrderNotify();
                    $setting = YySetting::findOne(['store_id' => $this->store_id]);
                    break;
                default:
                    return false;

            }
            if ($setting) {
                $is_sms = $setting->is_sms;
                $is_mail = $setting->is_mail;
                $is_print = $setting->is_print;
            }
            $order = $this->order;
            // 后台订单提醒
            OrderMessage::set($order->id, $order->store_id, $this->order_type, 0);
            \Yii::warning('is_sms:' . $is_sms . 'is_mail:' . $is_mail . 'is_print:' . $is_print);
            // 短信发送
            if ($is_sms == 1) {
                \Yii::warning('is_sms:' . $is_sms);
                Sms::send($order->store_id, $order->order_no);
            }
            // 邮件发送
            if ($is_mail == 1) {
                \Yii::warning('is_mail:' . $is_mail);
                $mail = new SendMail($order->store_id, $order->id, $this->order_type);
                $mail->send();
            }
            // 订单打印
            if ($is_print == 1) {
                \Yii::warning('is_print:' . $is_print);
                $printer_order = new PinterOrder($order->store_id, $order->id, 'pay', $this->order_type);
                $res = $printer_order->print_order();
            }
            // 向商户发送模板消息
            if (isset($this->order->mch_id) && $this->order->mch_id) {
                $this->tplMsgToMch();
            }
            // 微信公众号模板消息
            $this->tplMsgToAdmin();
            // 微信商品好物圈
            if (\Yii::$app->user->identity->platform == 0) {
                $wechatAccessToken = $this->wechat->getAccessToken();
                $res = CommonShoppingList::buyList($wechatAccessToken, $this->order, $this->order_type);
            }
        } catch (\Exception $e) {
            \Yii::error('line->>>' . $e->getLine());
            \Yii::error($e->getMessage());
            \Yii::error('订单支付完成后操作异常');
        }
    }

    //商城
    private function OrderNotify()
    {
        /* @var Order $order */
        $order = $this->order = Order::findOne(['id' => $this->order_id]);
        $this->updateFormIdIsUsable($order->order_no);
        //余额支付记录保存
        if ($order->pay_type == 3) {
            $log = new UserAccountLog();
            $log->user_id = $order->user_id;
            $log->type = 2;
            $log->price = $order->pay_price;
            $log->desc = "商城余额支付，订单号为：{$order->order_no}。";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 1;
            $log->save();
        }

        $this->store_id = $order->store_id;
        //发送模板消息
        if ($order->pay_type == 1 || $order->pay_type == 3) {
            $wechat_tpl_meg_sender = new WechatTplMsgSender($order->store_id, $order->id, $this->wechat);
            $wechat_tpl_meg_sender->payMsg();
        }

        // 首次付款，绑定上下级
        $user = User::findOne($order->user_id);
        if ($user->parent_id == 0) {
            $form = new BindForm();
            $form->user_id = $order->user_id;
            $form->store_id = $this->store_id;
            $form->parent_id = $user->parent_user_id;
            $form->condition = 2;
            $bindForm = $form->save();
            \Yii::warning('分销关系绑定');

            // 绑定上下级成功后的那个订单也算分销佣金
            if ($bindForm['code'] == 0) {
                $form = new ShareMoneyForm();
                $form->order = $this->order;
                $form->order_type = $this->order_type;
                $form->setData();
            }

        }

        //消费满指定金额自动成为分销商
        $this->autoBecomeShare($order->user_id, $order->store_id, 'STORE');
        // 购买指定或任意商品自动成为分销商
        $this->autoBuyGood($order->user_id, $order->store_id, $order->id);
        //首页购买提示
        $this->buyData($order->order_no, $order->store_id, 1);
        if (in_array($this->order->pay_type, [1, 3])) {
            //支付成功赠送优惠券
            $this->paySendCoupon($order->store_id, $order->user_id);
            //支付成功赠送卡券
            $this->paySendCard($order->store_id, $order->user_id, $order->id);
        }
        return true;
    }

    //秒杀
    private function MsOrderNotify()
    {
        /* @var MsOrder $order */
        $order = $this->order = MsOrder::findOne(['id' => $this->order_id]);
        $this->updateFormIdIsUsable($order->order_no);
        //余额支付记录保存
        if ($order->pay_type == 3) {
            $log = new UserAccountLog();
            $log->user_id = $order->user_id;
            $log->type = 2;
            $log->price = $order->pay_price;
            $log->desc = "秒杀余额支付，订单号为：{$order->order_no}。";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 2;
            $log->save();
        }
        $this->store_id = $order->store_id;
        //发送模板消息
        if ($order->pay_type == 1 || $order->pay_type == 3) {
            $wechat_tpl_meg_sender = new MsWechatTplMsgSender($order->store_id, $order->id, $this->wechat);
            $wechat_tpl_meg_sender->payMsg();
        }
        //首页购买提示
        $this->buyData($order->order_no, $order->store_id, 3);
        return true;
    }

    //拼团
    private function PtOrderNotify()
    {
        /* @var PtOrder $order */
        $order = $this->order = PtOrder::findOne(['id' => $this->order_id]);
        $this->updateFormIdIsUsable($order->order_no);
        //余额支付记录保存
        if ($order->pay_type == 3) {
            $log = new UserAccountLog();
            $log->user_id = $order->user_id;
            $log->type = 2;
            $log->price = $order->pay_price;
            $log->desc = "拼团余额支付，订单号为：{$order->order_no}。";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 3;
            $log->save();
        }

        //消费满指定金额自动成为分销商
        $this->autoBecomeShare($order->user_id, $order->store_id, 'PINTUAN');
        $this->store_id = $order->store_id;

        if ($order->parent_id == 0 && $order->is_group == 1) {
            $pid = $order->id;
        } elseif ($order->is_group == 1) {
            $pid = $order->parent_id;
        } else {
            $pid = -1;
        }
        //判断拼团是否成功
        if ($order->getSurplusGruop() <= 0) {
            $orderList = PtOrder::find()
                ->andWhere(['or', ['id' => $pid], ['parent_id' => $pid]])
                ->andWhere(['status' => 2, 'is_group' => 1])
                ->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]])
                ->all();
            foreach ($orderList as $val) {
                $val->is_success = 1;
                $val->success_time = time();
                $val->status = 3;
                $val->save();
            }
            //发送模板消息
            $notice = new PtNoticeSender($this->wechat, $order->store_id);
            $notice->sendSuccessNotice($order->id);
        }

        //首页购买提示
        $this->buyData($order->order_no, $order->store_id, 4);
        return true;
    }

    //预约
    private function YyOrderNotify()
    {
        /* @var YyOrder $order */
        $order = $this->order = YyOrder::findOne(['id' => $this->order_id]);
        $this->updateFormIdIsUsable($order->order_no);
        $this->store_id = $order->store_id;
        //发送模板消息
        $wechat_tpl_meg_sender = new YyWechatTplMsgSender($order->store_id, $order->id, $this->wechat);
        $wechat_tpl_meg_sender->payMsg();
        //首页购买提示
        $this->buyData($order->order_no, $order->store_id, 2);
        return true;
    }

    /**
     * 购买成功首页提示
     */
    private function buyData($order_no, $store_id, $type)
    {
        switch ($type) {
            case 1:
                $order = Order::find()->select(['u.nickname', 'g.name', 'u.avatar_url', 'od.goods_id'])->alias('c')
                    ->where('c.order_no=:order', [':order' => $order_no])
                    ->andwhere('c.store_id=:store_id', [':store_id' => $store_id])
                    ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
                    ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=c.id')
                    ->leftJoin(['g' => Goods::tableName()], 'od.goods_id = g.id')
                    ->asArray()->one();
                break;
            case 2:
                $order = YyOrder::find()->select(['u.nickname', 'g.name', 'u.avatar_url', 'g.id as goods_id'])->alias('c')
                    ->where('c.order_no=:order', [':order' => $order_no])
                    ->andwhere('c.store_id=:store_id', [':store_id' => $store_id])
                    ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
                    ->leftJoin(['g' => YyGoods::tableName()], 'c.goods_id = g.id')
                    ->asArray()->one();
                break;
            case 3:
                $order = MsOrder::find()->select(['u.nickname', 'g.name', 'u.avatar_url', 'c.goods_id'])->alias('c')
                    ->where('c.order_no=:order', [':order' => $order_no])
                    ->andwhere('c.store_id=:store_id', [':store_id' => $store_id])
                    ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
                    ->leftJoin(['g' => MsGoods::tableName()], 'c.goods_id = g.id')
                    ->asArray()->one();

                $goods = MiaoshaGoods::find()->select(['*'])->where(['open_date' => date("Y-m-d"), 'is_delete' => 0, 'goods_id' => $order['goods_id']])
                    ->andwhere('store_id=:store_id', [':store_id' => $store_id])
                    ->andWhere(['>', 'start_time', date("H")])
                    ->asArray()->one();
                $order['goods_id'] = $goods['id'];
                break;
            case 4:
                $order = PtOrder::find()->select(['u.nickname', 'g.name', 'u.avatar_url', 'od.goods_id'])->alias('c')
                    ->where('c.order_no=:order', [':order' => $order_no])
                    ->andwhere('c.store_id=:store_id', [':store_id' => $store_id])
                    ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
                    ->leftJoin(['od' => PtOrderDetail::tableName()], 'od.order_id=c.id')
                    ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id = g.id')
                    ->asArray()->one();
                break;
            default:
                return;
        }

        $key = "buy_data";
        $data = (object)null;
        $data->type = $type;
        $data->store_id = $store_id;
        $data->order_no = $order_no;
        $data->user = $order['nickname'];
        $data->goods = $order['goods_id'];
        $data->address = $order['name'];
        $data->avatar_url = $order['avatar_url'];
        $data->time = time();
        $new = json_encode($data);
        $cache = \Yii::$app->cache;
        $cache->set($key, $new, 300);
    }

    /**
     * 支付成功送优惠券
     */
    private function paySendCoupon($store_id, $user_id)
    {
        $form = new CouponPaySendForm();
        $form->store_id = $store_id;
        $form->user_id = $user_id;
        $form->save();
    }

    /**
     * 消费满指定金额自动成为分销商
     * @param $user_id integer 用户id
     */
    private function autoBecomeShare($user_id, $store_id, $type = null)
    {
        $auto_share_val = floatval(Option::get('auto_share_val', $store_id, 'share', 0));
        if ($auto_share_val == 0) {
            return;
        }

        $share = Share::findOne(['user_id' => $user_id, 'is_delete' => 0, 'store_id' => $store_id]);
        if ($share && $share->status == 1) {
            return;
        }
        if ($type === 'STORE') {
            $consumption_sum = Order::find()->where(['user_id' => $user_id, 'is_delete' => 0, 'is_pay' => 1])->sum('pay_price');
            $consumption_sum = floatval(($consumption_sum ? $consumption_sum : 0));
        } else if ($type === 'PINTUAN') {
            $consumption_sum = PtOrder::find()->where(['user_id' => $user_id, 'is_delete' => 0, 'is_pay' => 1])->sum('pay_price');
            $consumption_sum = floatval(($consumption_sum ? $consumption_sum : 0));
        } else {
            return;
        }

        if ($consumption_sum < $auto_share_val) {
            return;
        }
        if (!$share || $share->status == 2) {
            $share = new Share();
            $share->user_id = $user_id;
            $share->mobile = '';
            $share->name = '';
            $share->is_delete = 0;
            $share->store_id = $store_id;
        }

        $share->status = 1;
        $share->addtime = time();
        $share->save();

        $user = User::findOne($user_id);
        $user->time = time();
        $user->is_distributor = 1;
        $user->save();
    }

    /**
     * 购买指定商城成为分销商
     */
    public function autoBuyGood($user_id, $store_id, $order_id)
    {
        $setting = Setting::findOne(['store_id' => $store_id]);
        // 购买商城成分销商关闭状态不执行
        if ($setting->share_good_status == 0) {
            return;
        }

        $share = Share::findOne(['user_id' => $user_id, 'is_delete' => 0, 'store_id' => $store_id]);
        if ($share && $share->status == 1) {
            return;
        }

        $goodIds = OrderDetail::find()->where(['order_id' => $order_id])->select('goods_id')->all();
        $sign = false;

        // 购买任意商品
        if ($setting->share_good_status == 1) {
            $sign = true;
        }

        // 购买指定商品自动成为分销商
        if ($setting->share_good_status == 2) {
            foreach ($goodIds as $item) {
                if ($setting->share_good_id == $item->goods_id) {
                    $sign = true;
                    break;
                }
            }
        }

        if ($sign) {
            if (!$share || $share->status == 2) {
                $share = new Share();
                $share->user_id = $user_id;
                $share->mobile = '';
                $share->name = '';
                $share->is_delete = 0;
                $share->store_id = $store_id;
            }

            $share->status = 1;
            $share->addtime = time();
            $share->save();

            $user = User::findOne($user_id);
            $user->time = time();
            $user->is_distributor = 1;
            $user->save();
        }
    }

    /**
     * 支付成功送卡券
     */
    private function paySendCard($store_id, $user_id, $order_id)
    {
        $form = new CardSendForm();
        $form->store_id = $store_id;
        $form->user_id = $user_id;
        $form->order_id = $order_id;
        $form->save();
    }

    //售后订单申请成功，相关操作
    public function refund()
    {
        $this->wechat = $this->getWechat();
        try {
            $is_sms = 0;
            $is_mail = 0;
            if ($this->order_type == 0) {
                $is_sms = 1;
                $is_mail = 1;
                $order = Order::findOne(['id' => $this->order_id]);
            } elseif ($this->order_type == 1) {
                $ms_setting = MsSetting::findOne(['store_id' => $this->store_id]);
                $is_sms = $ms_setting->is_sms;
                $is_mail = $ms_setting->is_mail;
                $order = MsOrder::findOne(['id' => $this->order_id]);
            } elseif ($this->order_type == 2) {
                $pt_setting = PtSetting::findOne(['store_id' => $this->store_id]);
                $is_sms = $pt_setting->is_sms;
                $is_mail = $pt_setting->is_mail;
                $order = PtOrder::findOne(['id' => $this->order_id]);
            } elseif ($this->order_type == 3) {
                $yy_setting = YySetting::findOne(['store_id' => $this->store_id]);
                $is_sms = $yy_setting->is_sms;
                $is_mail = $yy_setting->is_mail;
                $order = YyOrder::findOne(['id' => $this->order_id]);
            } else {
                return false;
            }
            OrderMessage::set($order->id, $order->store_id, $this->order_type, 1);
            if ($is_sms == 1) {
                Sms::send_refund($this->store_id, $order->order_no);
            }
            if ($is_mail == 1) {
                $mail = new SendMail($order->store_id, $order->id, $this->order_type);
                $mail->send_refund();
            }

            $res = CommonFormId::save([
                [
                    'form_id' => $this->form_id
                ]
            ]);

        } catch (\Exception $e) {

        }
    }

    /**
     * 向入驻商发送小程序模板消息
     */
    public function tplMsgToMch()
    {
        $order = $this->order;
        if (!$order) {
            return;
        }
        $user = User::find()->where(['id' => Mch::find()->select('user_id')->where(['id' => $order->mch_id])])->one();
        if (!$user) {
            \Yii::warning('向入驻商发送模板消息失败：没有找到用户。');
            return;
        }

        // 用户来源
        $is_alipay = $user->platform == 1;
        if ($is_alipay) {
            $tpl = [
                'order' => TplMsgForm::get($this->store_id)->mch_tpl_2
            ];
        } else {
            $tpl = Option::get('mch_tpl_msg', $order->store_id);
        }
        if (!$tpl || !$tpl['order']) {
            \Yii::warning('向入驻商发送模板消息失败：模板消息ID未配置。');
            return;
        }

        $user_form_id = CommonFormId::getFormId($user->wechat_open_id);

        if (empty($user_form_id)) {
            \Yii::warning('向入驻商发送模板消息失败：无可用FormID。');
            return;
        }
        $data = [
            'touser' => $user->wechat_open_id,
            'template_id' => $tpl['order'],
            'page' => 'mch/m/myshop/myshop',
            'form_id' => $user_form_id->form_id,
            'data' => [
                'keyword1' => [
                    'color' => '',
                    'value' => $order->order_no,
                ],
                'keyword2' => [
                    'color' => '',
                    'value' => '您有一笔新的订单，请及时处理。',
                ],
            ],
        ];


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

            if ($response->isSuccess() === false) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($response->getError(), JSON_UNESCAPED_UNICODE));
            }
        } else {

            $wechat = $this->getWechat();
            $at = $wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$at}";
            \Yii::trace('--模板消息发送开始--');
            $wechat->curl->post($api, json_encode($data, JSON_UNESCAPED_UNICODE));
            if (!$wechat->curl->response) {
                \Yii::warning('模板消息发送失败：' . $wechat->curl->error_message);
                return;
            }
            $res = json_decode($wechat->curl->response, true);
            if ($res && $res['errcode'] == 0) {
                $user_form_id->send_count = $user_form_id->send_count + 1;
                $user_form_id->save();
                return;
            }
            if ($res && ($res['errcode'] == 41028 || $res['errcode'] == 41029)) {
                \Yii::warning('模板消息发送失败：' . $res['errmsg']);
                $user_form_id->send_count = $user_form_id->send_count + 1;
                $user_form_id->save();
                return;
            }
            if ($res && $res['errcode']) {
                \Yii::warning('模板消息发送失败：' . $res['errmsg']);
            }
        }
    }

    //通过公众号向管理员发送模板消息
    private function tplMsgToAdmin()
    {
        //订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单
        $type_list = [
            0 => '商城订单',
            1 => '秒杀订单',
            2 => '拼团订单',
            3 => '预约订单',
        ];
        $goods_str = '';
        switch ($this->order_type) {
            case 0:
                $goods_list = OrderDetail::find()
                    ->alias('od')
                    ->select('g.id,g.name')
                    ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                    ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])
                    ->asArray()->all();
                break;
            /* 其它几个插件的模板消息暂无发送功能，先注释留着
            case 1:
                $goods_list = MsOrder::find()->alias('o')
                    ->select('g.id,g.name')
                    ->leftJoin(['g' => MsGoods::tableName()], 'o.goods_id=g.id')
                    ->where(['o.id' => $this->order->id])
                    ->asArray()->all();
                break;
            case 2:
                $goods_list = PtOrderDetail::find()
                    ->alias('od')
                    ->select('g.id,g.name')
                    ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
                    ->where(['od.order_id' => $this->order->id, 'od.is_delete' => 0])
                    ->asArray()->all();
                break;
            case 3:
                $goods_list = YyOrder::find()
                    ->alias('o')
                    ->select('g.id,g.name')
                    ->leftJoin(['g' => YyGoods::tableName()], 'o.goods_id=g.id')
                    ->where(['o.id' => $this->order->id,])
                    ->asArray()->all();
                break;
            */
            default:
                break;
        }
        if ($goods_list && is_array($goods_list)) {
            foreach ($goods_list as $item)
                $goods_str .= $item['name'];
        }
        AdminTplMsgSender::sendNewOrder($this->store_id, [
            'time' => date('Y-m-d H:i:s'),
            'type' => $type_list[$this->order_type],
            'user' => $this->order->name,
            'goods' => $goods_str,
        ]);
    }

    /**
     * 更新 prepay_id 可用状态
     */
    public function updateFormIdIsUsable($orderNo)
    {
        // 更新 prepay_id 可用状态，支付成功后才可使用
        $formId = FormId::findOne([
            'order_no' => $orderNo,
            'type' => 'prepay_id',
        ]);
        \Yii::warning('更新 formId 状态' . $formId->id);
        if ($formId) {
            $formId->is_usable = 1;
            $res = $formId->save();

            \Yii::warning('支付成功, prepay_id生效:' . $res);
        }
    }
}
