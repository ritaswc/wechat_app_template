<?php

/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/21
 * Time: 14:25
 */

namespace app\modules\mch\controllers\book;

use app\models\User;
use app\models\UserAccountLog;
use app\models\YyGoods;
use app\models\YyOrder;
use app\models\YyWechatTplMsgSender;
use app\modules\mch\models\book\OrderForm;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\order\OrderClerkForm;
use app\modules\mch\models\order\OrderDeleteForm;
use app\utils\Refund;

class OrderController extends Controller
{

    /**
     * @return string
     * 订单列表
     */
    public function actionIndex()
    {
        $form = new OrderForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $arr = $form->getList();

        $f = new ExportList();
        $f->order_type = 3;
        $exportList = $f->getList();

        return $this->render('index', [
            'list' => $arr['list'],
            'pagination' => $arr['p'],
            'row_count' => $arr['row_count'],
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    public function actionRefund()
    {
        $order_id = \Yii::$app->request->get('id');
        $status = \Yii::$app->request->get('status');
        $remark = \Yii::$app->request->get('remark');
        $order = YyOrder::find()
            ->andWhere([
                'id' => $order_id,
                'is_delete' => 0,
                'store_id' => $this->store->id,
                'is_pay' => 1,
                'is_refund' => 0,
                'apply_delete' => 1,
            ])
            ->one();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单错误1',
            ];
        }

        if ($order->pay_price < 0) {
            return [
                'code' => 1,
                'msg' => '订单错误2',
            ];
        }

        /** @var Wechat $wechat */
        $wechat = isset(\Yii::$app->controller->wechat) ? \Yii::$app->controller->wechat : null;

        if ($status == 2) {
            $order->refund_time = time();
            $order->is_refund = 2;
            if ($order->save()) {
                $msg_sender = new YyWechatTplMsgSender($this->store->id, $order->id, $wechat);
                $remark = $remark ? $remark : '商家拒绝了您的取消退款';
                $refund_reason = '用户申请取消';
                $msg_sender->refundMsg($order->pay_price, $refund_reason, $remark);

                return [
                    'code' => 0,
                    'msg' => '拒绝退款成功',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '拒绝退款失败',
                ];
            }
        }

        if ($order->pay_type == 1) {
            $res = Refund::refund($order, $order->order_no, $order->pay_price);
            if ($res !== true) {
                return $res;
            }
        }

        $t = \Yii::$app->db->beginTransaction();

        if ($order->pay_type == 2) {
            $user = User::findOne(['id' => $order->user_id, 'store_id' => $this->store->id]);
            //余额支付 退换余额
            $user->money += floatval($order->pay_price);
            $log = new UserAccountLog();
            $log->user_id = $user->id;
            $log->type = 1;
            $log->price = $order->pay_price;
            $log->desc = "预约订单退款,订单号（{$order->order_no}）";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 11;
            $log->save();

            if (!$user->save()) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => $this->getErrorResponse($user),
                ];
            }
        }
        $order->refund_time = time();
        $order->is_refund = 1;
        if ($order->save()) {
            $t->commit();
            $msg_sender = new YyWechatTplMsgSender($this->store->id, $order->id, $wechat);
            if ($order->is_pay) {
                $remark = $remark ? $remark : '商家同意了您的退款请求';
                $refund_reason = '用户申请取消';
                $msg_sender->refundMsg($order->pay_price, $refund_reason, $remark);
            }
            //库存恢复
            $goods = YyGoods::findOne($order->goods_id);
            $attr_id_list = [];
            foreach (json_decode($order->attr) as $item) {
                array_push($attr_id_list, $item->attr_id);
            }
            if (!$goods->use_attr) {
                $goods->stock++;
            }
            $goods->numAdd($attr_id_list, 1);

            return [
                'code' => 0,
                'msg' => '订单已退款',
            ];
        } else {
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => '订单退款失败',
            ];
        }
    }

// 删除订单（软删除）
    public function actionDelete($order_id = null)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\YyOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->delete();
    }

// 清空回收站
    public function actionDeleteAll()
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\YyOrder';
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->deleteAll();
    }

// 移入移出回收站
    public function actionRecycle($order_id = null, $is_recycle = 0)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\YyOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->is_recycle = $is_recycle;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->recycle();
    }

// 核销订单
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->attributes = \Yii::$app->request->get();
        $form->order_model = 'app\models\YyOrder';
        $form->order_type = 3;
        $form->store = $this->store;
        return $form->clerk();
    }

//订单详情
    public function actionDetail($order_id = null)
    {
        $order = YyOrder::find()->where(['is_delete' => 0, 'store_id' => $this->store->id, 'id' => $order_id])->asArray()->one();
        if (!$order) {
            $url = \Yii::$app->urlManager->createUrl(['mch/book/order/index']);
            $this->redirect($url)->send();
        }
        $form = new OrderForm();
        $goods_list = $form->getOrderGoodsList($order['goods_id'], $order['id']);

        $user = User::find()->where(['id' => $order['user_id'], 'store_id' => $this->store->id])->asArray()->one();

        return $this->render('detail', [
            'order' => $order,
            'goods_list' => $goods_list,
            'user' => $user,
        ]);
    }

//添加备注
    public function actionSellerComments()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $seller_comments = \Yii::$app->request->get('seller_comments');
        $form = YyOrder::find()->where(['store_id' => $this->store->id, 'id' => $order_id])->one();
        $form->seller_comments = $seller_comments;
        if ($form->save()) {
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }
}
