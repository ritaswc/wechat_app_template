<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/24
 * Time: 11:31
 */

namespace app\utils;


use Alipay\AlipayRequestFactory;
use app\models\IntegralOrder;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Order;
use app\models\OrderRefund;
use app\models\OrderUnion;
use app\models\PtOrder;
use app\models\PtOrderRefund;
use app\models\YyOrder;
use app\modules\api\models\ApiModel;
use yii\helpers\VarDumper;

class Refund
{
    /**
     * @param $order Order|IntegralOrder|MsOrder|OrderUnion|PtOrder|YyOrder 订单
     * @param $refundFee integer 退款金额
     * @param $orderRefundNo string 退款单号
     * @return array|bool
     */
    public static function refund($order, $orderRefundNo, $refundFee)
    {
        $model = new Refund();
        $user = $order->user;
        if ($user->platform == 0) {
            return $model->wxRefund($order, $refundFee, $orderRefundNo);
        } else if ($user->platform == 1) {
            return $model->alipayRefund($order, $refundFee);
        } else {
            return [
                'code' => 1,
                'msg' => '退款失败'
            ];
        }
    }

    /**
     * 微信支付退款
     * @param $order
     * @param $refundFee
     * @param $orderRefundNo
     * @param null $refund_account
     * @return array|bool
     */
    private function wxRefund($order, $refundFee, $orderRefundNo, $refund_account = null)
    {
        if (isset($order->pay_price)) {
            $payPrice = $order->pay_price;
        } else {
            // 联合订单支付的总额
            $payPrice = $order->price;
        }
        $wechat = ApiModel::getWechat();
        $data = [
            'out_trade_no' => $order->order_no,
            'out_refund_no' => $orderRefundNo,
            'total_fee' => $payPrice * 100,
            'refund_fee' => $refundFee * 100,
        ];

        if (isset($order->order_union_id) && $order->order_union_id != 0) {
            // 多商户合并订单退款
            $orderUnion = OrderUnion::findOne($order->order_union_id);
            if (!$orderUnion) {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，合并支付订单不存在。',
                ];
            }
            $data['out_trade_no'] = $orderUnion->order_no;
            $data['total_fee'] = $orderUnion->price * 100;
        }

        if ($refund_account) {
            $data['refund_account'] = $refund_account;
        }
        $res = $wechat->pay->refund($data);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '订单取消失败，退款失败，服务端配置出错',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '订单取消失败，退款失败，' . $res['return_msg'],
                'res' => $res,
            ];
        }
        if (isset($res['err_code']) && $res['err_code'] == 'NOTENOUGH' && !$refund_account) {
            // 交易未结算资金不足，请使用可用余额退款
            return $this->wxRefund($order, $refundFee, $orderRefundNo, 'REFUND_SOURCE_RECHARGE_FUNDS');
        }
        if ($res['result_code'] != 'SUCCESS') {
            $refundQuery = $wechat->pay->refundQuery($order->order_no);
            if ($refundQuery['return_code'] != 'SUCCESS') {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，退款失败，' . $refundQuery['return_msg'],
                    'res' => $refundQuery,
                ];
            }
            if ($refundQuery['result_code'] == 'FAIL') {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，退款失败，' . $res['err_code_des'],
                    'res' => $res,
                ];
            }
            if ($refundQuery['result_code'] != 'SUCCESS') {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，退款失败，' . $refundQuery['err_code_des'],
                    'res' => $refundQuery,
                ];
            }
            if ($refundQuery['refund_status_0'] != 'SUCCESS') {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，退款失败，' . $refundQuery['err_code_des'],
                    'res' => $refundQuery,
                ];
            }
        }
        return true;
    }

    private function alipayRefund($order, $refundFee)
    {
        $request = AlipayRequestFactory::create('alipay.trade.refund', [
            'biz_content' => [
                'out_trade_no' => $order->order_no,
                'refund_amount' => $refundFee,
            ]
        ]);
        $aop = ApiModel::getAlipay($order->store_id);
        try {
            $res = $aop->execute($request)->getData();
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }
        if ($res['code'] != 10000) {
            return [
                'code' => 1,
                'msg' => $res['sub_msg']
            ];
        }
        return true;
    }
}
