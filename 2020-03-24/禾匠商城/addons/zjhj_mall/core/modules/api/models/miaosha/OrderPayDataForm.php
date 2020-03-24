<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 12:11
 */

namespace app\modules\api\models\miaosha;

use Alipay\AlipayRequestFactory;
use app\models\common\api\CommonOrder;
use app\models\FormId;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsSetting;
use app\models\OrderWarn;
use app\models\Setting;
use app\models\User;
use app\modules\api\models\ApiModel;
use app\modules\api\models\ShareMoneyForm;

/**
 * @property User $user
 * @property MsOrder $order
 */
class OrderPayDataForm extends ApiModel
{
    public $store_id;
    public $order_id;
    public $pay_type;
    public $user;
    public $form_id;

    private $wechat;
    private $order;
    public $parent_user_id;

    public function rules()
    {
        return [
            [['order_id', 'pay_type'], 'required'],
            [['pay_type'], 'in', 'range' => ['ALIPAY', 'WECHAT_PAY', 'HUODAO_PAY', 'BALANCE_PAY']],
            [['form_id'], 'string'],
            ['parent_user_id', 'integer']
        ];
    }

    public function search()
    {
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->order = MsOrder::findOne([
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$this->order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }

        if ($this->order->limit_time <= time()) {
            return [
                'code' => 1,
                'msg' => '该订单超过有效支付时间',
            ];
        }
        $commonOrder = CommonOrder::saveParentId($this->parent_user_id);

        $goods_names = MsGoods::find()->andWhere(['id' => $this->order->goods_id])->select('name')->scalar();
        try {
            $miaosha_goods = MiaoshaGoods::find()->where([
                'goods_id' => $this->order->goods_id, 'start_time' => date('H', $this->order->addtime),
                'open_date' => date('Y-m-d', $this->order->addtime), 'is_delete' => 0
            ])->orderBy(['id' => SORT_DESC])->one();
            if ($miaosha_goods->buy_max > 0) {
                if ($miaosha_goods->buy_max < $this->order->num) {
                    throw new \Exception("购买数量超过限制！ 商品“" . $goods_names . '”最多允许购买' . $miaosha_goods->buy_max . '件，请返回重新下单');
                }
            }
            if ($miaosha_goods->buy_limit > 0) {
                $buy_limit = MsOrder::find()
                    ->andWhere([
                        'user_id' => $this->order->user_id, 'is_cancel' => 0, 'goods_id' => $this->order->goods_id, 'is_delete' => 0,
                    ])->andWhere([
                        'between',
                        'addtime',
                        strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':00:00'),
                        strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':59:59')
                    ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]])
                    ->count();

                if ($buy_limit >= $miaosha_goods->buy_limit) {
                    throw new \Exception('当前活动限购' . $miaosha_goods->buy_limit . '单');
                }
            }
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }

        $goods_names = mb_substr($goods_names, 0, 32, 'utf-8');
        $this->setReturnData($this->order);

        if ($this->pay_type == 'WECHAT_PAY') {

            // 支付宝支付
            if (\Yii::$app->fromAlipayApp()) {
                $request = AlipayRequestFactory::create('alipay.trade.create', [
                    'notify_url' => pay_notify_url('/alipay-notify.php'),
                    'biz_content' => [
                        'body' => $goods_names, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                        'subject' => $goods_names, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                        'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                        'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                        'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID
                        
                    ],
                ]);

                $aop = $this->getAlipay();
                $res = $aop->execute($request)->getData();

                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => $res,
                    'res' => $res,
                    'body' => $goods_names,
                ];
            }

            // 微信支付
            if (\Yii::$app->fromWechatApp()) {
                $res = $this->unifiedOrder($goods_names);
                if (isset($res['code']) && $res['code'] == 1) {
                    return $res;
                }

                //记录prepay_id发送模板消息用到
                FormId::addFormId([
                    'store_id' => $this->store_id,
                    'user_id' => $this->user->id,
                    'wechat_open_id' => $this->user->wechat_open_id,
                    'form_id' => $res['prepay_id'],
                    'type' => 'prepay_id',
                    'order_no' => $this->order->order_no,
                ]);

                $pay_data = [
                    'appId' => $this->wechat->appId,
                    'timeStamp' => '' . time(),
                    'nonceStr' => md5(uniqid()),
                    'package' => 'prepay_id=' . $res['prepay_id'],
                    'signType' => 'MD5',
                ];
                $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => (object)$pay_data,
                    'res' => $res,
                    'body' => $goods_names,
                ];
            }
        }

        if ($this->pay_type == 'HUODAO_PAY' || $this->pay_type == 'BALANCE_PAY') {
            $order = MsOrder::findOne(['id' => $this->order_id, 'store_id' => $this->store_id]);

            $order->pay_type = 2;
            //余额支付  用户余额变动
            if ($this->pay_type == 'BALANCE_PAY') {
                $user = User::findOne(['id' => $this->order->user_id]);
                if ($user->money < $this->order->pay_price) {
                    return [
                        'code' => 1,
                        'msg' => '支付失败，余额不足',
                    ];
                }
                $user->money -= floatval($this->order->pay_price);
                $user->save();
                $order->pay_type = 3;
                $order->is_pay = 1;
                $order->pay_time = time();
            }
            if ($order->save()) {
                //支付完成之后，相关操作
                $form = new OrderWarn();
                $form->order_id = $order->id;
                $form->order_type = 1;
                $form->notify();
//                //发送短信提醒
                //                Sms::send($order->store_id, $order->order_no);
                //                //发送邮件提醒
                //                $mail = new SendMail($order->store_id,$order->id,0);
                //                $mail->send();
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => '',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败',
                    'data' => $this->getErrorResponse($order),
                ];
            }
        }
    }

    /**
     * 设置佣金
     */
    private function setReturnData($order)
    {
        $form = new ShareMoneyForm();
        $form->order = $order;
        $form->order_type = 1;
        return $form->setData();
    }

    /**
     * 设置佣金
     * @param MsOrder $order
     * 2018-05-23 风哀伤 废弃
     */
    private function setReturnData_1($order)
    {
        $ms_setting = MsSetting::findOne(['store_id' => $order->store_id]);
        if ($ms_setting->is_share == 0) {
            return;
        }
        $setting = Setting::findOne(['store_id' => $order->store_id]);
        if (!$setting || $setting->level == 0) {
            return;
        }
        $user = User::findOne($order->user_id); //订单本人
        if (!$user) {
            return;
        }
        $order->parent_id = $user->parent_id;
        $parent = User::findOne($user->parent_id); //上级
        if ($parent->parent_id) {
            $order->parent_id_1 = $parent->parent_id;
            $parent_1 = User::findOne($parent->parent_id); //上上级
            if ($parent_1->parent_id) {
                $order->parent_id_2 = $parent_1->parent_id;
            } else {
                $order->parent_id_2 = -1;
            }
        } else {
            $order->parent_id_1 = -1;
            $order->parent_id_2 = -1;
        }
        $order_total = doubleval($order->total_price - $order->express_price);
        $pay_price = doubleval($order->pay_price - $order->express_price);

        $goods = MsGoods::findOne(['id' => $order->goods_id, 'store_id' => $this->store_id]);
        $share_commission_money_first = 0; //一级分销总佣金
        $share_commission_money_second = 0; //二级分销总佣金
        $share_commission_money_third = 0; //三级分销总佣金
        $rebate = 0; //自购返利
        if ($goods['individual_share'] == 1) {
            $rate_first = doubleval($goods['share_commission_first']);
            $rate_second = doubleval($goods['share_commission_second']);
            $rate_third = doubleval($goods['share_commission_third']);
            $rate_rebate = doubleval($goods['rebate']);
            if ($goods['share_type'] == 1) {
                $share_commission_money_first += $rate_first * $order['num'];
                $share_commission_money_second += $rate_second * $order['num'];
                $share_commission_money_third += $rate_third * $order['num'];
                $rebate += $rate_rebate * $order['num'];
            } else {
                $share_commission_money_first += $pay_price * $rate_first / 100;
                $share_commission_money_second += $pay_price * $rate_second / 100;
                $share_commission_money_third += $pay_price * $rate_third / 100;
                $rebate += $pay_price * $rate_rebate / 100;
            }
        } else {
            $rate_first = doubleval($setting->first);
            $rate_second = doubleval($setting->second);
            $rate_third = doubleval($setting->third);
            $rate_rebate = doubleval($setting->rebate);
            if ($setting->price_type == 1) {
                $share_commission_money_first += $rate_first * $order['num'];
                $share_commission_money_second += $rate_second * $order['num'];
                $share_commission_money_third += $rate_third * $order['num'];
                $rebate += $rate_rebate * $order['num'];
            } else {
                $share_commission_money_first += $pay_price * $rate_first / 100;
                $share_commission_money_second += $pay_price * $rate_second / 100;
                $share_commission_money_third += $pay_price * $rate_third / 100;
                $rebate += $pay_price * $rate_rebate / 100;
            }
        }
        //下单用户不是分销商，则不参与自购返利
        if ($user->is_distributor == 0) {
            $rebate = 0;
        }
        if ($setting->is_rebate == 0) {
            $rebate = 0;
        }

        $order->first_price = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
        $order->second_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
        $order->third_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
        $order->rebate = $rebate < 0.01 ? 0 : $rebate;
        $order->save();
    }

    private function unifiedOrder($goods_names)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $goods_names,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->pay_price * 100,
            'notify_url' => pay_notify_url('/pay-notify.php'),
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') { //商户订单号重复
                $this->order->order_no = (new OrderSubmitForm())->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($goods_names);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }
}
