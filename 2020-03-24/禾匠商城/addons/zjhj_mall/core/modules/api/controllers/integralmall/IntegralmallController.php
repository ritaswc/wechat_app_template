<?php

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/14
 * Time: 17:00
 */

namespace app\modules\api\controllers\integralmall;

use app\models\IntegralOrder;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\controllers\Controller;
use app\modules\api\models\integralmall\IntegralForm;
use app\modules\api\models\CouponListForm;
use app\modules\api\models\integralmall\CouponOrderForm;
use app\modules\mch\models\integralmall\IntegralGoodsForm;
use app\modules\api\models\integralmall\OrderSubmitPreviewForm;
use app\modules\api\models\QrcodeForm;
use app\modules\api\models\integralmall\RegisterForm;

class IntegralmallController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    public function actionIndex()
    {
        $form = new IntegralForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->index());
    }

    public function actionExplain()
    {
        $form = new IntegralForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->id;
        $form->today = \Yii::$app->request->get('today');
        return new \app\hejiang\BaseApiResponse($form->explain());
    }

    //我的兑换
    public function actionExchange()
    {
        $form = new IntegralForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->exchange());
    }

    //积分明细
    public function actionIntegralDetail()
    {
        $form = new IntegralForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->id;
        $form->attributes = \Yii::$app->request->get();
        return new \app\hejiang\BaseApiResponse($form->detail());
    }

    //优惠券详情
    public function actionCouponInfo()
    {
        $form = new CouponListForm();
        $form->id = \Yii::$app->request->get('coupon_id');
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->couponInfo());
    }

    //兑换优惠券
    public function actionExchangeCoupon()
    {
        $form = new CouponOrderForm();
        $get = \Yii::$app->request->get();
        $form->id = $get['id'];
        $form->user = \Yii::$app->user->identity;
        $form->type = $get['type'];
        $form->store_id = $this->store_id;
        return new \app\hejiang\BaseApiResponse($form->exchangeCoupon());
    }

    public function actionGoodsList()
    {
        $form = new IntegralForm();
        $form->attributes = \Yii::$app->request->get();
        return new \app\hejiang\BaseApiResponse($form->getGoodsList());
    }

    //商品详情
    public function actionGoodsInfo()
    {
        $form = new IntegralGoodsForm();
        $form->id = \Yii::$app->request->get('id');
        $form->store_id = $this->store->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //订单详情
    public function actionSubmitPreview()
    {
        $form = new OrderSubmitPreviewForm();
        $data = \Yii::$app->request->get();
        $data['goods_list'] = json_encode($data['goods_list']);
        $form->attributes = $data;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    public function actionSubmit()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->version = hj_core_version();
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    //订单列表
    public function actionList()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->orderList());
    }

    //取消订单
    public function actionRevoke()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->revoke());
    }

    //付款
    public function actionOrderSubmit()
    {
        $form = new OrderSubmitPreviewForm();
        $form->order_id = \Yii::$app->request->get('id');
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new \app\hejiang\BaseApiResponse($form->orderPay());
    }

    //订单收货
    public function actionConfirm()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->confirm());
    }

    //核销码
    public function actionGetQrcode()
    {
        $order_no = \Yii::$app->request->get('order_no');
        $form = new QrcodeForm();
        $form->page = "pages/integral-mall/clerk/clerk";
        $form->width = 100;
        if (\Yii::$app->fromAlipayApp()) {
            $form->scene = "order_no={$order_no}";
        } else {
            $form->scene = "{$order_no}";
        }
        $form->store = $this->store;
        $res = $form->getQrcode();
        return new \app\hejiang\BaseApiResponse($res);
    }

    //核销订单详情
    public function actionClerkOrderDetails()
    {
        $id = \Yii::$app->request->get('id');
        $type = \Yii::$app->request->get('type');
        if ($type == 'exchange') {
            $order = IntegralOrder::find()
                ->where([
                    'store_id' => $this->store_id,
                    'order_no' => $id,
                ])->with(['user' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                    ]);
                }])->with(['shop' => function ($query) {
                    $query->where([
                        'is_delete' => 0,
                    ]);
                }])->with(['detail' => function ($query) {
                    $query->where([
                        'is_delete' => 0,
                    ]);
                }])
                ->asArray()->one();
        } else {
            $order = IntegralOrder::find()
                ->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                    'order_no' => $id,
                ])->with(['user' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                        'is_delete' => 0,
                    ]);
                }])->with(['shop' => function ($query) {
                    $query->where([
                        'is_delete' => 0,
                    ]);
                }])->with(['detail' => function ($query) {
                    $query->where([
                        'is_delete' => 0,
                    ]);
                }])
                ->asArray()->one();
        }
        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单不存在');
        }
        $order['detail']['attr'] = json_decode($order['detail']['attr']);
        $order['addtime'] = date('Y-m-d H:i:s', $order['addtime']);
        $data = [
            'code' => 0,
            'data' => $order
        ];
        return new \app\hejiang\BaseApiResponse($data);
    }

    //核销订单
    public function actionClerk()
    {
        if (\Yii::$app->user->identity->is_clerk != 1) {
            return new \app\hejiang\ApiResponse(1, '不是核销员禁止核销');
        }
        $id = \Yii::$app->request->get('order_id');
        $integralOrder = IntegralOrder::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
            ])->andWhere(['or', ['id' => $id], ['order_no' => $id]])->one();
        if (!$integralOrder) {
            return new \app\hejiang\ApiResponse(1, '订单不存在');
        }
        if ($integralOrder->is_pay == 0) {
            return new \app\hejiang\ApiResponse(1, '订单未支付');
        }
        if ($integralOrder->clerk_id != null && $integralOrder->clerk_id != -1) {
            return new \app\hejiang\ApiResponse(1, '订单已核销');
        }
        $integralOrder->is_send = 1;
        $integralOrder->shop_id = \Yii::$app->user->identity->shop_id;
        $integralOrder->send_time = time();
        $integralOrder->is_confirm = 1;
        $integralOrder->confirm_time = time();
        $integralOrder->clerk_id = \Yii::$app->user->identity->id;
        if ($integralOrder->save()) {
            return new \app\hejiang\ApiResponse(0, '核销完成');
        }
    }

    public function actionRegister()
    {
        $today = \Yii::$app->request->get('today');
        $form = new RegisterForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->register_time = $today;
        return new \app\hejiang\BaseApiResponse($form->save());
    }
}
