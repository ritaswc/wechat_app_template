<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/14
 * Time: 11:28
 */

namespace app\modules\api\controllers\book;

use app\models\OrderWarn;
use app\models\Shop;
use app\models\YyGoods;
use app\models\YyOrder;
use app\models\YyOrderForm;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\book\OrderClerkForm;
use app\modules\api\models\book\OrderCommentForm;
use app\modules\api\models\book\OrderCommentPreview;
use app\modules\api\models\book\OrderListForm;
use app\modules\api\models\book\OrderPreviewFrom;
use app\modules\api\models\QrcodeForm;

class OrderController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    /**
     * 订单预览
     */
    public function actionSubmitPreview()
    {
        $form = new OrderPreviewFrom();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->goods_id = \Yii::$app->request->get('gid');
        $form->attr = \Yii::$app->request->get('attr');
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * 订单提交
     */
    public function actionSubmit()
    {
        $form = new OrderPreviewFrom();
        $model = \Yii::$app->request->post();
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->goods_id = $model['gid'];
        $form->pay_type = $model['pay_type'] ? $model['pay_type']:'WECHAT_PAY';
        $form->form_list = json_decode($model['form_list'], true);
        $form->form_id = $model['form_id'];
        $form->attr = $model['attr'];
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    /**
     * 订单列表
     */
    public function actionList()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }


    /**
     * @param int $id
     * 用户取消
     */
    public function actionCancel($id = 0)
    {
        $order = YyOrder::find()
            ->andWhere([
                'is_delete' => 0,
                'store_id' => $this->store->id,
                'user_id' => \Yii::$app->user->id,
                'is_cancel' => 0,
                'id' => $id,
            ])->one();

        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单不存在，或已取消');
        }
  
        $t = \Yii::$app->db->beginTransaction();

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

        //取消
        $order->is_cancel = 1;
        if ($order->save()) {
            $t->commit();
            return new \app\hejiang\ApiResponse(0, '取消成功');
        } else {
            $t->rollBack();
            return new \app\hejiang\ApiResponse(1, '取消失败');
        }
    }

    /**
     * @param int $id
     * 订单列表支付按钮
     */
    public function actionPayData($id = 0)
    {
        $form = new OrderPreviewFrom();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->pay_type = \Yii::$app->request->get('pay_type');
        return new \app\hejiang\BaseApiResponse($form->payData($id));
    }

    /**
     * @param int $id
     * 订单详情
     */
    public function actionOrderDetails($id = 0)
    {
        $order = YyOrder::find()
            ->alias('o')
            ->select([
                'o.*',
                'g.name', 'g.original_price', 'g.shop_id', 'g.cover_pic', 'g.id AS g_id'
            ])
            ->andWhere([
                'o.is_delete' => 0,
                'o.store_id' => $this->store->id,
                'o.user_id' => \Yii::$app->user->id,
                'o.is_cancel' => 0,
                'o.id' => $id,
            ])
            ->leftJoin(['g' => YyGoods::tableName()], 'g.id=o.goods_id')
            ->asArray()->one();
        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单不存在，或已取消');
        }

        $orderForm = YyOrderForm::find()
            ->andWhere(['store_id' => $this->store->id, 'order_id' => $order['id']])
            ->select('key,value,type')
            ->asArray()
            ->all();
        $order['orderForm'] = $orderForm;
        $shopList = [];
        if (!empty($order['shop_id'])) {
            $shopId = explode(',', trim($order['shop_id'], ','));
            $shopList = Shop::find()
                ->andWhere(['id' => $shopId])
                ->andWhere(['store_id' => $this->store_id,'is_delete'=>0])
                ->asArray()
                ->all();
        } else {
            $shopList = Shop::find()
                ->andWhere(['store_id' => $this->store_id,'is_delete'=>0])
                ->asArray()
                ->all();
        }
        $order['shopListNum'] = count($shopList);

        $order['shopList'] = $shopList;
        $order['addtime'] = date('Y-m-d H:i:s', $order['addtime']);
        return new \app\hejiang\ApiResponse(0, 'success', $order);
    }

    /**
     * @param int $id
     * 核销订单详情
     */
    public function actionClerkOrderDetails($id = 0)
    {
        $order = YyOrder::find()
            ->alias('o')
            ->select([
                'o.*',
                'g.name', 'g.original_price', 'g.shop_id', 'g.cover_pic', 'g.id AS g_id'
            ])
            ->andWhere([
                'o.is_delete' => 0,
                'o.store_id' => $this->store->id,
                'o.is_cancel' => 0,
                'o.id' => $id,
            ])
            ->leftJoin(['g' => YyGoods::tableName()], 'g.id=o.goods_id')
            ->asArray()->one();
        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单不存在，或已取消');
        }
        $order['attr'] = \Yii::$app->serializer->decode($order['attr']);
        $orderForm = YyOrderForm::find()
            ->andWhere(['store_id' => $this->store->id, 'order_id' => $order['id']])
            ->select('key,value,type')
            ->asArray()
            ->all();
        $order['orderForm'] = $orderForm;
        $shopList = [];
        if (!empty($order['shop_id'])) {
            $shopId = explode(',', trim($order['shop_id'], ','));
            $shopList = Shop::find()
                ->andWhere(['id' => $shopId])
                ->andWhere(['store_id' => $this->store_id,'is_delete'=>0])
                ->asArray()
                ->all();
        } else {
            $shopList = Shop::find()
                ->andWhere(['store_id' => $this->store_id,'is_delete'=>0])
                ->asArray()
                ->all();
        }
        $order['shopListNum'] = count($shopList);

        $order['shopList'] = $shopList;
        $order['addtime'] = date('Y-m-d H:i:s', $order['addtime']);
        return new \app\hejiang\ApiResponse(0, 'success', $order);
    }


    /**
     * @return mixed|string
     * 核销订单二维码
     */
    public function actionGetQrcode()
    {
        $order_no = \Yii::$app->request->get('order_no');
        $order = YyOrder::findOne(['order_no' => $order_no, 'store_id' => $this->store->id]);

        $form = new QrcodeForm();
        $form->page = "pages/book/clerk/clerk";
        $form->width = 100;
        if (\Yii::$app->fromAlipayApp()) {
            $form->scene = "order_id={$order->id}";
        } else {
            $form->scene = "{$order->id}";
        }
        $form->store = $this->store;
        $res = $form->getQrcode();
        return new \app\hejiang\BaseApiResponse($res);
    }

    /**
     * 核销订单
     */
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->order_id = \Yii::$app->request->get('order_id');
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    /**
     * 用户申请退款
     */
    public function actionApplyRefund()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $order = YyOrder::find()
            ->andWhere([
                'id' => $order_id,
                'is_delete' => 0,
                'store_id' => $this->store->id,
                'user_id' => \Yii::$app->user->id,
                'is_pay' => 1,
                'is_refund' => 0,
                'apply_delete' => 0,
            ])
            ->one();
        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单错误');
        }
        if ($order->pay_price >= 0.01) {
            $order->apply_delete = 1;
        } else {
            $order->apply_delete = 1;
            $order->is_refund = 1;
        }
        if ($order->save()) {
            $form = new OrderWarn();
            $form->store_id = $order['store_id'];
            $form->order_id = $order['id'];
            $form->order_type = 3;
            $form->refund();
            return new \app\hejiang\ApiResponse(0, '退款申请成功');
        } else {
            return new \app\hejiang\ApiResponse(1, '退款申请失败,请重试');
        }
    }

    /**
     * 评论预览页面
     */
    public function actionCommentPreview()
    {
        $form = new OrderCommentPreview();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * 订单评论提交
     */
    public function actionComment()
    {
        $form = new OrderCommentForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }
}
