<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 12:11
 */

namespace app\modules\api\models\group;

use app\models\common\api\CommonOrder;
use app\models\FormId;
use app\models\GoodsShare;
use app\models\OrderShare;
use app\models\OrderWarn;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtSetting;
use app\models\Setting;
use app\models\User;
use app\modules\api\models\ApiModel;
use app\modules\api\models\ShareMoneyForm;
use app\models\PtGoodsDetail;
use Alipay\AlipayRequestFactory;

/**
 * @property User $user
 * @property PtOrder $order
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
            [['order_id', 'pay_type',], 'required'],
            [['pay_type'], 'in', 'range' => ['ALIPAY', 'WECHAT_PAY', 'HUODAO_PAY', 'BALANCE_PAY']],
            [['form_id'], 'string'],
            [['parent_user_id'], 'integer']
        ];
    }

    public function search()
    {
        $order = PtOrder::findOne(['id' => $this->order_id, 'store_id' => $this->store_id]);
        $orderDetail = PtOrderDetail::find()->where(['order_id' => $order->id])->with('goods')->one();
        /* @var \app\models\ptGoods $goods */
        $goods = $orderDetail->goods;
        if ($goods->buy_limit > 0) {
            $orderNum = PtOrder::getCount($goods->id, $order->user_id);
            if ($orderNum >= $goods->buy_limit) {
                return [
                    'code' => 1,
                    'msg' => '您已超过该商品购买次数',
                ];
            }
        }

        if ($order->parent_id) {
            $parentOrder = PtOrder::findOne([
                'id' => $order->parent_id,
                'is_delete' => 0,
                'store_id' => $this->store_id,
                'status' => 2,
            ]);
            if (!$parentOrder) {
                return [
                    'code' => 1,
                    'msg' => '您参与的团不存在，或已满',
                ];
            }
        }
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->order = PtOrder::findOne([
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$this->order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }

        $commonOrder = CommonOrder::saveParentId($this->parent_user_id);

        $goods_names = '';
        $goods_list = PtOrderDetail::find()->alias('od')->leftJoin(['g' => PtGoods::tableName()], 'g.id=od.goods_id')->where([
            'od.order_id' => $this->order->id,
            'od.is_delete' => 0,
        ])->select('g.name')->asArray()->all();
        foreach ($goods_list as $goods) {
            $goods_names .= $goods['name'] . ';';
        }
        $goods_names = mb_substr($goods_names, 0, 32, 'utf-8');
        $this->setReturnData($this->order);
        if ($this->pay_type == 'WECHAT_PAY') {
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
            $order = PtOrder::findOne(['id' => $this->order_id, 'store_id' => $this->store_id]);

            $order->pay_type = 2;
            //余额支付  用户余额变动
            if ($this->pay_type == 'BALANCE_PAY') {
                $user = User::findOne(['id' => $order->user_id]);
                if ($user->money < $order->pay_price) {
                    return [
                        'code' => 1,
                        'msg' => '支付失败，余额不足'
                    ];
                }
                $user->money -= floatval($order->pay_price);
                $user->save();
                $order->pay_type = 3;
                $order->is_pay = 1;
                $order->pay_time = time();
            }
            $order->status = 2;
            $order_detail = PtOrderDetail::find()
                ->andWhere(['order_id' => $order->id, 'is_delete' => 0])
                ->one();
            $goods = PtGoods::findOne(['id' => $order_detail->goods_id]);

            if ($order->parent_id == 0 && $order->is_group == 1) {
                // 团购-团长
                $pid = $order->id;


                if ($order->class_group) {
                    $group = PtGoodsDetail::findOne(['id' => $order->class_group, 'store_id' => $this->store_id]);
                    $order->limit_time = (time() + (int)$group->group_time * 3600);
                } else {
                    $order->limit_time = (time() + (int)$goods->grouptime * 3600);
                }
            } elseif ($order->is_group == 1) {
                // 团购-参团
                $pid = $order->parent_id;
                $parentOrder = PtOrder::findOne([
                    'id' => $pid,
                    'is_delete' => 0,
                    'store_id' => $order->store_id,
                    'status' => 3,
                    'is_success' => 1,
                ]);
                if ($parentOrder) {
                    // 该订单参与的团已经成团
                    $order->limit_time = time();
                    $order->parent_id = 0;
                }
            } else {
                // 单独购买
                $order->status = 3;
                $order->is_success = 1;
                $order->success_time = time();
            }
            if ($order->save()) {
                //支付完成之后，相关操作；
                $form = new OrderWarn();
                $form->order_id = $order->id;
                $form->order_type = 2;
                $form->notify();
//                $printer_setting = PrinterSetting::findOne(['store_id' => $order->store_id, 'is_delete' => 0]);
//                $type = json_decode($printer_setting->type, true);
//                if ($type['pay'] && $type['pay'] == 1) {
//                    $printer_order = new PrinterPtOrder($order->store_id, $order->id);
//                    $res = $printer_order->print_order();
//                }
//                if ($order->getSurplusGruop()<=0){
//                    $orderList = PtOrder::find()
//                        ->andWhere(['or',['id'=>$pid],['parent_id'=>$pid]])
//                        ->andWhere(['status'=>2,'is_pay'=>1,'is_group'=>1])
//                        ->all();
//                    foreach ($orderList AS $val){
//                        $val->is_success = 1;
//                        $val->success_time = time();
//                        $val->status = 3;
//                        $val->save();
//                    }
//                }
//                //发送短信提醒
//                Sms::send($order->store_id, $order->order_no);
//                //发送后台消息
//                OrderMessage::set($order->id, $order->store_id);
//                //打印订单
//                $printer_setting = PrinterSetting::findOne(['store_id' => $order->store_id, 'is_delete' => 0]);
//                $type = json_decode($printer_setting->type, true);
//                if ($type['pay'] && $type['pay'] == 1) {
//                    $printer_order = new PrinterPtOrder($order->store_id, $order->id);
//                    $res = $printer_order->print_order();
//                }
//                //发送邮件提醒
//                $mail = new SendMail($order->store_id, $order->id, 2);
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

    /**
     * 设置佣金
     */
    private function setReturnData($order)
    {
        $form = new ShareMoneyForm();
        $form->order = $order;
        $form->order_type = 2;
        return $form->setData();
    }

    /**
     * 设置佣金
     * @param PtOrder $pt_order
     * @param int $type
     * 2018-05-23 风哀伤 废弃
     */
    private function setReturnData_1($pt_order, $type = 0)
    {
        //拼团设置是否开启分销
        $pt_setting = PtSetting::findOne(['store_id' => $this->store_id]);
        if (!$pt_setting || $pt_setting->is_share == 0) {
            return false;
        }
        //总设置是否开启分销
        $setting = Setting::findOne(['store_id' => $pt_order->store_id]);
        if (!$setting || $setting->level == 0) {
            return;
        }
        $user = User::findOne($pt_order->user_id);//订单本人
        if (!$user) {
            return;
        }
        $order = OrderShare::findOne(['order_id' => $pt_order->id, 'type' => $type, 'is_delete' => 0, 'store_id' => $this->store_id]);
        if (!$order) {
            $order = new OrderShare();
            $order->order_id = $pt_order->id;
            $order->store_id = $pt_order->store_id;
            $order->is_delete = 0;
            $order->version = hj_core_version();
            $order->user_id = $pt_order->user_id;
        }
        $order->type = $type;
        $order->parent_id_1 = $user->parent_id;
        $parent = User::findOne($user->parent_id);//上级
        if ($parent->parent_id) {
            $order->parent_id_2 = $parent->parent_id;
            $parent_1 = User::findOne($parent->parent_id);//上上级
            if ($parent_1->parent_id) {
                $order->parent_id_3 = $parent_1->parent_id;
            } else {
                $order->parent_id_3 = -1;
            }
        } else {
            $order->parent_id_2 = -1;
            $order->parent_id_3 = -1;
        }

        $order_detail_list = PtOrderDetail::find()->alias('od')->leftJoin(['g' => GoodsShare::tableName()], 'od.goods_id=g.goods_id and g.type = ' . $type)
            ->where(['od.is_delete' => 0, 'od.order_id' => $pt_order->id])
            ->asArray()
            ->select(['od.*', 'g.*'])
            ->all();
        $share_commission_money_first = 0;//一级分销总佣金
        $share_commission_money_second = 0;//二级分销总佣金
        $share_commission_money_third = 0;//三级分销总佣金
        $rebate = 0;//自购返利
        foreach ($order_detail_list as $item) {
            $item_price = doubleval($item['total_price']);
            if ($item['individual_share'] == 1) {
                $rate_first = doubleval($item['share_commission_first']);
                $rate_second = doubleval($item['share_commission_second']);
                $rate_third = doubleval($item['share_commission_third']);
                $rate_rebate = doubleval($item['rebate']);
                if ($item['share_type'] == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                    $rebate += $rate_rebate * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
            } else {
                $rate_first = doubleval($setting->first);
                $rate_second = doubleval($setting->second);
                $rate_third = doubleval($setting->third);
                $rate_rebate = doubleval($setting->rebate);
                if ($setting->price_type == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                    $rebate += $rate_rebate * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
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
}
