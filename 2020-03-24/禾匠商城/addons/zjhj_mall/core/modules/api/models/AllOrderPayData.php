<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 10:55
 */

namespace app\modules\api\models;

use app\models\common\CommonFormId;
use app\utils\PinterOrder;
use app\utils\SendMail;
use app\utils\Sms;
use app\models\FormId;
use app\models\Goods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderMessage;
use app\models\PrinterSetting;
use app\models\Setting;
use app\models\User;

/**
 * @property User $user
 */
class AllOrderPayData extends ApiModel
{

    public $store_id;
    public $order_id;
    public $pay_type;
    public $user;
    public $form_id;
    public $type;//订单类型 s--商城订单 ms--秒杀订单 pt--拼团订单 y--预约订单 re--充值订单

    private $wechat;
    private $order;
    private $goods_name;

    public function rules()
    {
        return [
            [['order_id', 'pay_type',], 'required'],
            [['pay_type'], 'in', 'range' => ['ALIPAY', 'WECHAT_PAY', 'HUODAO_PAY', 'BALANCE_PAY']],
            [['form_id','type'], 'string'],
            [['type'],'default','value'=>'s']
        ];
    }

    public function search()
    {
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->type == 's') {
            $this->order();
        }
        if ($this->type == 'ms') {
            $this->msOrder();
        }
        $goods_names = $this->goods_name;
        if ($this->pay_type == 'WECHAT_PAY') {
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
        //货到付款和余额支付数据处理
        if ($this->pay_type == 'HUODAO_PAY' || $this->pay_type == 'BALANCE_PAY') {
            //记录prepay_id发送模板消息用到
            $res = CommonFormId::save([
                [
                    'form_id' => $this->form_id
                ]
            ]);

            //余额支付  用户余额变动
            if ($this->pay_type == 'BALANCE_PAY') {
                $user = User::findOne(['id' => $this->order->user_id]);
                if ($user->money < $this->order->pay_price) {
                    return [
                        'code'=>1,
                        'msg'=>'支付失败，余额不足'
                    ];
                }
                $user->money -= floatval($this->order->pay_price);
                $user->save();
                $this->order->is_pay = 1;
                $this->order->pay_type = 3;
                $this->order->pay_time = time();
                $this->order->save();
            }
            //发送短信提醒
            Sms::send($this->order->store_id, $this->order->order_no);
            //发送后台消息
            OrderMessage::set($this->order->id, $this->order->store_id);
            //打印订单
            $printer_setting = PrinterSetting::findOne(['store_id' => $this->order->store_id, 'is_delete' => 0]);
            $type = json_decode($printer_setting->type, true);
            if ($type['pay'] && $type['pay'] == 1) {
                $printer_order = new PinterOrder($this->order->store_id, $this->order->id);
                $res = $printer_order->print_order();
            }
            //发送邮件提醒
            $mail = new SendMail($this->order->store_id, $this->order->id, 0);
            $mail->send();
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => '',
            ];
        }
    }

    /**
     * 设置佣金
     * @param Order $order
     */
    private function setReturnData($order)
    {
        $setting = Setting::findOne(['store_id' => $order->store_id]);
        if (!$setting || $setting->level == 0) {
            return;
        }
        $user = User::findOne($order->user_id);//订单本人
        if (!$user) {
            return;
        }
        $order->parent_id = $user->parent_id;
        $parent = User::findOne($user->parent_id);//上级
        if ($parent->parent_id) {
            $order->parent_id_1 = $parent->parent_id;
            $parent_1 = User::findOne($parent->parent_id);//上上级
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

        $order_detail_list = OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where(['od.is_delete' => 0, 'od.order_id' => $order->id])
            ->asArray()
            ->select('g.individual_share,g.share_commission_first,g.share_commission_second,g.share_commission_third,od.total_price,od.num,g.share_type')
            ->all();
        $share_commission_money_first = 0;//一级分销总佣金
        $share_commission_money_second = 0;//二级分销总佣金
        $share_commission_money_third = 0;//三级分销总佣金
        foreach ($order_detail_list as $item) {
            $item_price = doubleval($item['total_price']);
            if ($item['individual_share'] == 1) {
                $rate_first = doubleval($item['share_commission_first']);
                $rate_second = doubleval($item['share_commission_second']);
                $rate_third = doubleval($item['share_commission_third']);
                if ($item['share_type'] == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                }
            } else {
                $rate_first = doubleval($setting->first);
                $rate_second = doubleval($setting->second);
                $rate_third = doubleval($setting->third);
                if ($setting->price_type == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                }
            }
        }


        $order->first_price = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
        $order->second_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
        $order->third_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
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
            if ($res['err_code'] == 'INVALID_REQUEST') {//商户订单号重复
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

    //商城订单处理
    private function order()
    {
        $goods_names = '';
        $this->order = Order::findOne([
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$this->order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $goods_list = OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')->where([
            'od.order_id' => $this->order->id,
            'od.is_delete' => 0,
        ])->select('g.name')->asArray()->all();
        foreach ($goods_list as $goods) {
            $goods_names .= $goods['name'] . ';';
        }
        $goods_names = mb_substr($goods_names, 0, 32, 'utf-8');
        $this->setReturnData($this->order);
        $this->goods_name = $goods_names;
    }

    //秒杀订单处理
    private function msOrder()
    {
        $goods_names = '';
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
                'code'  => 1,
                'msg'   => '该订单超过有效支付时间',
            ];
        }
        $goods_names = MsGoods::find()->andWhere(['id'=>$this->order->goods_id])->select('name')->scalar();
        $goods_names = mb_substr($goods_names, 0, 32, 'utf-8');
        $this->goods_name = $goods_names;
    }
}
