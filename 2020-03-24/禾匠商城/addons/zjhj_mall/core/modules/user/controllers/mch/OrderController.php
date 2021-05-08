<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/9
 * Time: 17:57
 */

namespace app\modules\user\controllers\mch;

use app\models\common\admin\order\CommonUpdateAddress;
use app\models\common\user\order\ShareOrderForm;
use app\models\Express;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderForm;
use app\models\OrderRefund;
use app\models\User;
use app\models\WechatTplMsgSender;
use app\modules\api\models\OrderRevokeForm;
use app\modules\user\behaviors\MchBehavior;
use app\modules\user\behaviors\UserLoginBehavior;
use app\modules\user\controllers\Controller;
use app\modules\user\models\mch\OrderListForm;
use app\modules\user\models\mch\OrderPriceForm;
use app\modules\user\models\mch\OrderRefundForm;
use app\modules\user\models\mch\OrderRefundListForm;
use app\modules\user\models\mch\OrderSendForm;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'login' => [
                'class' => UserLoginBehavior::className(),
            ],
            'mch' => [
                'class' => MchBehavior::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $form = new OrderListForm();
        $form->store_id = $this->store->id;
        $form->mch_id = $this->mch->id;
        $form->limit = 10;
        $form->attributes = \Yii::$app->request->get();
        $data = $form->search();
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'express_list' => $this->getExpressList(),
        ]);
    }

    //订单发货
    public function actionSend()
    {
        $form = new OrderSendForm();
        $post = \Yii::$app->request->post();
        if ($post['is_express'] == 1) {
            $form->scenario = 'EXPRESS';
        }
        $form->attributes = $post;
        $form->store_id = $this->store->id;
        $form->mch_id = $this->mch->id;
        return $form->save();
    }

    private function getExpressList()
    {
        $storeExpressList = Order::find()
            ->select('express')
            ->where([
                'AND',
                ['store_id' => $this->store->id],
                ['is_send' => 1],
                ['!=', 'express', ''],
            ])->groupBy('express')->orderBy('send_time DESC')->limit(5)->asArray()->all();
        $expressLst = Express::getExpressList();
        $newStoreExpressList = [];
        foreach ($storeExpressList as $i => $item) {
            $newStoreExpressList[] = $item['express'];
        }

        $newPublicExpressList = [];
        foreach ($expressLst as $i => $item) {
            $newPublicExpressList[] = $item['name'];
        }

        return [
            'private' => $newStoreExpressList,
            'public' => $newPublicExpressList,
        ];
    }

    //售后订单列表
    public function actionRefund()
    {
        if (\Yii::$app->request->isPost) {
            $form = new OrderRefundForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->mch_id = $this->mch->id;
            return $form->save();
        } else {
            $form = new OrderRefundListForm();
            $form->attributes = \Yii::$app->request->get();
            $form->store_id = $this->store->id;
            $form->mch_id = $this->mch->id;
            $form->limit = 10;
            $data = $form->search();

            return $this->render('refund', [
                'row_count' => $data['row_count'],
                'pagination' => $data['pagination'],
                'list' => $data['list'],
            ]);
        }
    }

    //订单取消申请处理
    public function actionApplyDeleteStatus($id, $status)
    {
        $order = Order::findOne([
            'id' => $id,
            'apply_delete' => 1,
            'is_delete' => 0,
            'store_id' => $this->store->id,
            'mch_id' => $this->mch->id
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新页面后重试',
            ];
        }
        if ($status == 1) {//同意
            $form = new OrderRevokeForm();
            $form->order_id = $order->id;
            $form->delete_pass = true;
            $form->user_id = $order->user_id;
            $form->store_id = $order->store_id;
            $res = $form->save();
            if ($res['code'] == 0) {
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            } else {
                return $res;
            }
        } else {//拒绝
            $order->apply_delete = 0;
            $order->save();
            $msg_sender = new WechatTplMsgSender($this->store->id, $order->id, $this->wechat);
            $msg_sender->revokeMsg('您的取消申请已被拒绝');
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        }
    }

    public function actionDetail($order_id = null)
    {
        $order = Order::find()->where(['store_id' => $this->store->id, 'id' => $order_id, 'mch_id' => $this->mch->id])->asArray()->one();
        if (!$order) {
            $url = \Yii::$app->urlManager->createUrl(['user/mch/order/index']);
            $this->redirect($url)->send();
        }
        $order['integral_arr'] = json_decode($order['integral'], true);

        $order['get_integral'] = OrderDetail::find()
            ->andWhere(['order_id' => $order['id'], 'is_delete' => 0])
            ->select([
                'sum(integral)'
            ])->scalar();

        $form = new OrderListForm();
        $goods_list = $form->getOrderGoodsList($order['id']);
        $user = User::find()->where(['id' => $order['user_id'], 'store_id' => $this->store->id])->asArray()->one();
        $order_form = OrderForm::find()->where(['order_id' => $order['id'], 'is_delete' => 0, 'store_id' => $this->store->id])->asArray()->all();
        $order_refund = OrderRefund::findOne(['store_id' => $this->store->id, 'order_id' => $order['id'], 'is_delete' => 0]);
        if ($order_refund) {
            $order['refund'] = $order_refund->status;
        }
        return $this->render('detail', [
            'order' => $order,
            'goods_list' => $goods_list,
            'user' => $user,
            'order_form' => $order_form
        ]);
    }

    //移入回收站
    public function actionEdit()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $is_recycle = \Yii::$app->request->get('is_recycle');
        if ($is_recycle == 0 || $is_recycle == 1) {
            $form = Order::find()->where(['store_id' => $this->store->id, 'mch_id' => $this->mch->id])->andWhere('id = :order_id', [':order_id' => $order_id])->one();
            $form->is_recycle = $is_recycle;
            if ($form && $form->save()) {
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            }
        };
        return [
            'code' => 1,
            'msg' => '操作失败',
        ];
    }

//添加备注
    public function actionSellerComments()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $seller_comments = \Yii::$app->request->get('seller_comments');
        $form = Order::find()->where(['store_id' => $this->store->id, 'id' => $order_id, 'mch_id' => $this->mch->id])->one();
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

    //改价
    public function actionAddPrice()
    {
        $form = new OrderPriceForm();
        $form->mch_id = $this->mch->id;
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        return $form->update();
    }

    //货到付款，确认收货
    public function actionConfirm()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $order = Order::findOne(['id' => $order_id, 'mch_id' => $this->mch->id, 'store_id' => $this->store->id]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新重试'
            ];
        }
        if ($order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单支付方式不是货到付款，无法确认收货'
            ];
        }
        if ($order->is_send == 0) {
            return [
                'code' => 1,
                'msg' => '订单未发货'
            ];
        }
        $order->is_confirm = 1;
        $order->confirm_time = time();
        $order->is_pay = 1;
        $order->pay_time = time();
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            foreach ($order->errors as $error) {
                return [
                    'code' => 1,
                    'msg' => $error
                ];
            }
        }
    }

    // 更新订单地址
    public function actionUpdateOrderAddress()
    {
        $commonUpdateAddress = new CommonUpdateAddress();
        $commonUpdateAddress->data = \Yii::$app->request->post();
        $updateAddress = $commonUpdateAddress->updateAddress();

        return $updateAddress;
    }

    public function actionShare()
    {
        $form = new ShareOrderForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store = $this->store;
        $form->mch = $this->mch;
        $form->order_type = 's';
        $data = $form->search();
        $ignore = ['yy'];
        $type = [
            'ds' => '多商户订单',
        ];

        return $this->render('@app/modules/mch/views/share/order', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'ignore' => $ignore,
            'type' => $type
        ]);
    }
}
