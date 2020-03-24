<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 14:27
 */

namespace app\modules\mch\controllers\miaosha;

use app\models\common\api\CommonShoppingList;
use app\models\Express;
use app\models\MsOrder;
use app\models\MsWechatTplMsgSender;
use app\models\Order;
use app\models\OrderForm;
use app\models\OrderRefund;
use app\models\Shop;
use app\models\User;
use app\models\MsOrderRefund;
use app\modules\api\models\miaosha\OrderRevokeForm;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\miaosha\OrderListForm;
use app\modules\mch\models\miaosha\OrderRefundForm;
use app\modules\mch\models\miaosha\OrderRefundListForm;
use app\modules\mch\models\miaosha\OrderSendForm;
use app\modules\mch\models\order\OrderClerkForm;
use app\modules\mch\models\order\OrderDeleteForm;
use app\modules\mch\models\OrderPriceForm;
use app\modules\mch\models\PrintForm;
use app\modules\mch\models\StoreDataForm;
use app\modules\mch\extensions\Export;
use app\utils\TaskCreate;
use yii\web\UploadedFile;

class OrderController extends Controller
{
    public function actionIndex()
    {
        // 获取可导出数据
        $f = new ExportList();
        $exportList = $f->getList();

        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $data = $form->search();

        $store_data_form = new StoreDataForm();
        $store_data_form->store_id = $this->store->id;
        $store_data_form->is_offline = \Yii::$app->request->get('is_offline');
        $user_id = \Yii::$app->request->get('user_id');
        $clerk_id = \Yii::$app->request->get('clerk_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        $store_data_form->user_id = $user_id;
        $store_data_form->clerk_id = $clerk_id;
        $store_data_form->shop_id = $shop_id;
        if ($user_id) {
            $user = User::findOne(['store_id' => $this->store->id, 'id' => $user_id]);
        }
        if ($clerk_id) {
            $clerk = User::findOne(['store_id' => $this->store->id, 'id' => $clerk_id]);
        }
        if ($shop_id) {
            $shop = Shop::findOne(['store_id' => $this->store->id, 'id' => $shop_id]);
        }
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            //'count_data' => OrderListForm::getCountData($this->store->id),
            'store_data' => $store_data_form->search(),
            'express_list' => $this->getExpressList(),
            'user' => $user,
            'clerk' => $clerk,
            'shop' => $shop,
            'exportList' => \Yii::$app->serializer->encode($exportList)
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
        return $form->save();
    }

    private function getExpressList()
    {
        $storeExpressList = MsOrder::find()
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
            foreach ($expressLst as $value) {
                if ($value['name'] == $item['express']) {
                    $newStoreExpressList[] = $item['express'];
                    break;
                }
            }
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
        // 获取可导出数据
        $f = new ExportList();
        $f->order_type = 1;
        $f->type = 1;
        $exportList = $f->getList();
        $form = new OrderRefundListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $data = $form->search();

        return $this->render('refund', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    //订单取消申请处理
    public function actionApplyDeleteStatus($id, $status)
    {
        $order = MsOrder::findOne([
            'id' => $id,
            'apply_delete' => 1,
            'is_delete' => 0,
            'store_id' => $this->store->id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新页面后重试',
            ];
        }
        $remark = \Yii::$app->request->get('remark');
        if ($status == 1) { //同意
            $form = new OrderRevokeForm();
            $form->order_id = $order->id;
            $form->delete_pass = true;
            $form->user_id = $order->user_id;
            $form->store_id = $order->store_id;
            $res = $form->save();
            if ($res['code'] == 0) {
                $msg_sender = new MsWechatTplMsgSender($this->store->id, $order->id, $this->wechat);
                $msg_sender->revokeMsg($remark ? $remark : '商家同意了您的订单取消请求');
                $wechatAccessToken = $this->wechat->getAccessToken();
                $res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 1, 12);
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            } else {
                return $res;
            }
        } else { //拒绝
            $order->apply_delete = 0;
            $order->save();
            $msg_sender = new MsWechatTplMsgSender($this->store->id, $order->id, $this->wechat);
            $msg_sender->revokeMsg($remark ? $remark : '您的取消申请已被拒绝');
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        }
    }

    public function actionPrint()
    {
        $id = \Yii::$app->request->get('id');
        $express = \Yii::$app->request->get('express');
        $post_code = \Yii::$app->request->get('post_code');
        $form = new PrintForm();
        $form->store_id = $this->store->id;
        $form->order_id = $id;
        $form->express = $express;
        $form->post_code = $post_code;
        $form->order_type = 1;
        return $form->send();
    }

    public function actionAddPrice()
    {
        $form = new OrderPriceForm();
        $form->store_id = $this->store->id;
        $form->order_type = 'ms';
        $form->attributes = \Yii::$app->request->get();
        return $form->update();
    }

    public function actionDetail($order_id = null)
    {
        $order = MsOrder::find()->where(['is_delete' => 0, 'store_id' => $this->store->id, 'id' => $order_id])->asArray()->one();
        if (!$order) {
            $url = \Yii::$app->urlManager->createUrl(['mch/miaosha/order/index']);
            $this->redirect($url)->send();
        }
        $order['integral_arr'] = json_decode($order['integral'], true);
        $form = new OrderListForm();
        $goods_list = $form->getOrderGoodsList($order['id']);

        $user = User::find()->where(['id' => $order['user_id'], 'store_id' => $this->store->id])->asArray()->one();

        //$order_form = OrderForm::find()->where(['order_id' => $order['id'], 'is_delete' => 0, 'store_id' => $this->store_id])->asArray()->all();
        $order_refund = MsOrderRefund::findOne(['store_id' => $this->store->id, 'order_id' => $order['id'], 'is_delete' => 0]);
        if ($order_refund) {
            $order['refund'] = $order_refund->status;
        }
        return $this->render('detail', [
            'order' => $order,
            'goods_list' => $goods_list,
            'user' => $user,
            'order_form' => [],
            'is_update' => true,
        ]);
    }

    //添加备注
    public function actionSellerComments()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $seller_comments = \Yii::$app->request->get('seller_comments');
        $form = MsOrder::find()->where(['store_id' => $this->store->id, 'id' => $order_id])->one();
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

    public function actionOffline()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->is_offline = 1;
        $form->store_id = $this->store->id;
        $form->platform = \Yii::$app->request->get('platform');
        $form->limit = 10;
        $data = $form->search();

        $store_data_form = new StoreDataForm();
        $store_data_form->store_id = $this->store->id;
        $store_data_form->is_offline = 1;
        $user_id = \Yii::$app->request->get('user_id');
        $clerk_id = \Yii::$app->request->get('clerk_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        $store_data_form->user_id = $user_id;
        $store_data_form->clerk_id = $clerk_id;
        $store_data_form->shop_id = $shop_id;
        if ($user_id) {
            $user = User::findOne(['store_id' => $this->store->id, 'id' => $user_id]);
        }
        if ($clerk_id) {
            $clerk = User::findOne(['store_id' => $this->store->id, 'id' => $clerk_id]);
        }
        if ($shop_id) {
            $shop = Shop::findOne(['store_id' => $this->store->id, 'id' => $shop_id]);
        }
        // 获取可导出数据
        $f = new ExportList();
        $exportList = $f->getList();
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            //'count_data' => OrderListForm::getCountData($this->store->id),
            'store_data' => $store_data_form->search(),
            'express_list' => $this->getExpressList(),
            'user' => $user,
            'clerk' => $clerk,
            'shop' => $shop,
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    public function actionConfirm()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $order = MsOrder::findOne(['id' => $order_id]);
        if ($order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新重试',
            ];
        }
        if ($order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单支付方式不是货到付款，无法确认收货',
            ];
        }
        if ($order->is_send == 0) {
            return [
                'code' => 1,
                'msg' => '订单未发货',
            ];
        }
        $order->is_confirm = 1;
        $order->confirm_time = time();
        $order->is_pay = 1;
        $order->pay_time = time();
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($order->errors as $error) {
                return [
                    'code' => 1,
                    'msg' => $error,
                ];
            }
        }
    }

    //批量发货
    public function actionBatchShip()
    {
        if (\Yii::$app->request->isPost) {
            $file = \Yii::$app->request->post();
            if (!$file['url']) {
                return [
                    'code' => 1,
                    'msg' => '请输入模板地址'
                ];
            }
            if (!$file['express']) {
                return [
                    'code' => 1,
                    'msg' => '请输入快递公司'
                ];
            }
            $arrCSV = array();
            if (($handle = fopen($file['url'], "r")) !== false) {
                $key = 0;
                while (($data = fgetcsv($handle, 0, ",")) !== false) {
                    $c = count($data);
                    for ($x = 0; $x < $c; $x++) {
                        $arrCSV[$key][$x] = trim($data[$x]);
                    }
                    $key++;
                }
                fclose($handle);
            }
            unset($arrCSV[0]);
            $form = new OrderSendForm();
            $form->store_id = $this->store->id;
            $form->express = \Yii::$app->request->post('express');
            $info = $form->batch($arrCSV);

            return [
                'code' => 0,
                'msg' => '操作成功',
                'data' => $info,
            ];
        }
        return $this->render('batch-ship', [
            'express_list' => $this->getExpressList(),
        ]);
    }

    public function actionShipModel()
    {
        Export::shipModel();
    }

    // 处理售后订单
    // TODO 已废弃，移至和商城退款退货统一处理
    public function actionRefundHandle()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        return $form->save();
    }

    // 删除订单（软删除）
    public function actionDelete($order_id = null)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\MsOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->delete();
    }

    // 清空回收站
    public function actionDeleteAll()
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\MsOrder';
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->deleteAll();
    }

    // 移入移出回收站
    public function actionRecycle($order_id = null,$is_recycle = 0)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\MsOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->is_recycle = $is_recycle;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->recycle();
    }

    // 修改价格
    public function actionUpdatePrice()
    {
        $form = new \app\modules\mch\models\order\OrderPriceForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->order_type = 'ms';
        return $form->save();
    }

    // 核销订单
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->attributes = \Yii::$app->request->get();
        $form->order_model = 'app\models\MsOrder';
        $form->order_type = 1;
        $form->store = $this->store;
        return $form->clerk();
    }
}
