<?php
namespace app\modules\api\models;

use app\utils\PayNotify;
use app\models\LevelOrder;
use app\models\Level;

use app\utils\PinterOrder;
use app\utils\SendMail;
use app\utils\Sms;
use app\models\FormId;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderMessage;
use app\models\PrinterSetting;
use app\models\Setting;
use app\models\User;
use app\models\WechatTemplateMessage;
use app\models\WechatTplMsgSender;
use yii\helpers\VarDumper;
use app\models\UserAccountLog;
use Alipay\AlipayRequestFactory;

/**
 * @property User $user
 * @property ReOrder $order
 */
class OrderMemberForm extends ApiModel
{
    public $store_id;

    public $pay_type;
    public $level_id;
    public $form_id;
    public $order;
    public $user;
    public $wechat;

    public function rules()
    {
        return [
            [['level_id'],'number'],
            [['pay_type'],'in','range'=>['WECHAT_PAY','BALANCE_PAY']]
        ];
    }

    public function save()
    {
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }
 
        $id = Level::findone([
            'id' => $this->level_id,
            'is_delete' => 0,
            'store_id' =>$this->store_id
            ])->level;
        $level = Level::find()->select(['id','level','price'])
                    ->where(['store_id'=>$this->store_id,'is_delete'=>0, 'status' => 1])
                    ->andWhere(['<>','price', '0'])
                    ->andWhere(['>', 'level', $this->user->level])
                    ->andWhere(['<=', 'level', $id])
                    ->orderBy('level asc')->asArray()->all();
        if (!$level) {
            return 1;
        }
        $order = new LevelOrder();
        $order->store_id = $this->store_id;
        $order->user_id = $this->user->id;
        $order->current_level = $this->user->level;
        $order->after_level = $id;
        $order->order_no = self::getOrderNo();
        $order->is_pay = 0;
        $order->is_delete = 0;
        $order->addtime = time();

        $pay_price = 0;
        foreach ($level as $v) {
            $pay_price +=(float)$v['price'];
        }
        $order->pay_price = $pay_price;
        
        if ($order->save()) {
            $this->order = $order;
            if ($this->pay_type == 'WECHAT_PAY') {
                $body = "购买会员";

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

            //余额支付数据处理
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

                $log = new UserAccountLog();
                $log->user_id = $user->id;
                $log->type = 2;
                $log->price = floatval($this->order->pay_price);
                $log->addtime = time();
                $log->order_type = 7;
                $log->desc = '会员购买,订单号为：'.$order->order_no;
                $log->order_id = $order->id;
                $log->save();

                $order->is_pay = 1;
                $order->pay_time = time();
                $order->pay_type = 2;

                if ($order->save()) {
                    //支付完成之后，相关操作；
                    $user = User::findOne($order->user_id);
                    $user->level = $order->after_level;
                    $user->save();
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
        } else {
            return $this->getErrorResponse($order);
        }
    }

    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = 'L'.date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = LevelOrder::find()->where(['order_no' => $order_no])->exists();
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
