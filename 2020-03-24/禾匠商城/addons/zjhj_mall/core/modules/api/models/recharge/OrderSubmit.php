<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 14:17
 */

namespace app\modules\api\models\recharge;

use app\models\FormId;
use app\models\Recharge;
use app\models\ReOrder;
use app\models\User;
use app\modules\api\models\ApiModel;
use Alipay\AlipayRequestFactory;
/**
 * @property User $user
 * @property ReOrder $order
 */
class OrderSubmit extends ApiModel
{
    public $store_id;

    public $pay_price;
    public $send_price;
    public $pay_type;

    public $wechat;
    public $order;
    public $user;

    public function rules()
    {
        return [
            [['pay_price','pay_type'],'required'],
            [['send_price'],'number'],
            [['pay_type'],'in','range'=>['WECHAT_PAY']]
        ];
    }

    public function save()
    {
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $order = new ReOrder();
        $order->store_id = $this->store_id;
        $order->user_id = $this->user->id;
        if ($this->send_price != 0) {
            $exists = Recharge::find()->where([
                'store_id'=>$this->store_id,'pay_price'=>$this->pay_price,'send_price'=>$this->send_price,'is_delete'=>0
            ])->exists();
            if (!$exists) {
                return [
                    'code'=>1,
                    'msg'=>'充值失败，请重新充值'
                ];
            }
        }
        $order->pay_price = $this->pay_price;
        $order->send_price = $this->send_price;
        $order->order_no = self::getOrderNo();
        $order->is_pay = 0;
        $order->is_delete = 0;
        $order->addtime = time();
        if ($order->save()) {
            $this->order = $order;
            if ($this->pay_type == 'WECHAT_PAY') {
                $body = "充值";
                
                if (\Yii::$app->fromAlipayApp()) {
                    $request = AlipayRequestFactory::create('alipay.trade.create', [
                        'notify_url' => pay_notify_url('/re-alipay-notify.php'),
                        'biz_content' => [
                            'body' => $body, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                            'subject' => $body, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
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
                        'body' => $body,
                    ];
                }

                $res = $this->unifiedOrder($body);
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
                    'body' => $body,
                ];
            }
        } else {
            return $this->getErrorResponse($order);
        }
    }

    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = 're'.date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = ReOrder::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }

    private function unifiedOrder($body)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $body,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->pay_price * 100,
            'notify_url' => pay_notify_url('/re-pay-notify.php'),
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
                $this->order->order_no = $this->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($body);
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
