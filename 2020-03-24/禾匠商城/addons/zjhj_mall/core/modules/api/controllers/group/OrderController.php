<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:47
 */

namespace app\modules\api\controllers\group;

use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Shop;
use app\models\Store;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\group\ExpressDetailForm;
use app\modules\api\models\group\GroupForm;
use app\modules\api\models\group\OrderClerkForm;
use app\modules\api\models\group\OrderDetailForm;
use app\modules\api\models\group\OrderGoodsQrcodeForm;
use app\modules\api\models\group\OrderPayDataForm;
use app\modules\api\models\group\OrderRefundDetailForm;
use app\modules\api\models\group\OrderRefundForm;
use app\modules\api\models\group\OrderRefundPreviewForm;
use app\modules\api\models\group\OrderRevokeForm;
use app\modules\api\models\group\OrderSubmitForm;
use app\modules\api\models\group\OrderSubmitPreviewForm;
use app\modules\api\models\group\OrderCommentForm;
use app\modules\api\models\group\OrderCommentPreview;
use app\modules\api\models\group\OrderConfirmForm;
use app\modules\api\models\group\OrderListForm;
use app\modules\api\models\QrcodeForm;
use yii\helpers\VarDumper;
use app\models\PtGoodsDetail;

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

    //订单提交前的预览页面
    public function actionSubmitPreview()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //订单提交
    public function actionSubmit()
    {
        $form = new OrderSubmitForm();
        $model = \Yii::$app->request->post();
        if ($model['offline'] == 2) {
            $form->scenario = "OFFLINE";
        } else {
            $form->scenario = "EXPRESS";
        }
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    //订单支付数据
    public function actionPayData()
    {
        $form = new OrderPayDataForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //订单列表
    public function actionList()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //订单详情
    public function actionDetail()
    {
        $form = new OrderDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * 参团页面
     */
    public function actionGroup()
    {
        $form = new GroupForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->groupInfo());
    }

    //订单物流信息
    public function actionExpressDetail()
    {
        $form = new ExpressDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }
    //评论预览页面
    public function actionCommentPreview()
    {
        $form = new OrderCommentPreview();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    //评论提交
    public function actionComment()
    {
        $form = new OrderCommentForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    //订单确认收货
    public function actionConfirm()
    {
        $form = new OrderConfirmForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    //获取拼团二维码海报
    public function actionGoodsQrcode()
    {
        $form = new OrderGoodsQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        if (!\Yii::$app->user->isGuest) {
            $form->user_id = \Yii::$app->user->id;
        }
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * @return mixed|string
     * 核销订单二维码
     */
    public function actionGetQrcode()
    {
        $order_no = \Yii::$app->request->get('order_no');
        $order = PtOrder::findOne(['order_no'=>$order_no,'store_id'=>$this->store->id]);

        $form = new QrcodeForm();
        $form->page = "pages/pt/clerk/clerk";
        $form->width = 100;
        if(\Yii::$app->fromAlipayApp()){
            $form->scene = "order_id={$order->id}";
        }else{
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
     * 核销订单详情
     */
    public function actionClerkOrderDetails($id = 0)
    {
        $order = PtOrder::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return new \app\hejiang\ApiResponse(1, '订单不存在');
        }
        $status = [
            1=> '待付款',
            2=> '拼团中',
            3=> '拼团成功',
            4=> '拼团失败',
        ];
        $goods_list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->select('g.id,od.id AS order_detail_id,g.name,od.attr,od.num,od.total_price,od.pic')
            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->asArray()->all();
        $num = 0;
        foreach ($goods_list as $i => $item) {
            $goods_list[$i]['attr'] = json_decode($item['attr']);
            $num += intval($item['num']);
            $goods_list[$i]['goods_pic'] = $item['pic']?:PtGoods::getGoodsPicStatic($item['id'])->pic_url;
            if ($order->is_pay == 1 && $order->is_send == 1) {
                $goods_list[$i]['order_refund_enable'] = 1;
            } else {
                $goods_list[$i]['order_refund_enable'] = 0;
            }
            if ($order->is_confirm == 1) {
                $store = Store::findOne($this->store_id);
                if ((time() - $order->confirm_time) > $store->after_sale_time * 86400) {//超过可售后时间
                    $goods_list[$i]['order_refund_enable'] = 0;
                }
            }
        }
        $limit_time_res = [
            'days'  => '00',
            'hours' => '00',
            'mins'  => '00',
            'secs'  => '00',
        ];
        $timediff = $order->limit_time - time();
        $groupFail = 0;
        if ($timediff<=0) {
            $groupFail = 1;     // 拼团失败
        }
        $limit_time_res['days'] = intval($timediff/86400)>0?intval($timediff/86400):0;
        //计算小时数
        $remain = $timediff%86400;
        $limit_time_res['hours'] = intval($remain/3600)>0?intval($remain/3600):0;
        //计算分钟数
        $remain = $remain%3600;
        $limit_time_res['mins'] = intval($remain/60)>0?intval($remain/60):0;
        //计算秒数
        $limit_time_res['secs'] = $remain%60>0?$remain%60:0;
        $limit_time_ms = explode('-', date('Y-m-d-H-i-s', $order->limit_time));

        if ($order->shop_id) {
            $shop = Shop::find()->select(['name','mobile','address','longitude','latitude'])->where(['store_id'=>$this->store_id,'id'=>$order->shop_id])->asArray()->one();
        }

        $data = array(
            'order_id' => $order->id,
            'is_pay' => $order->is_pay,
            'is_send' => $order->is_send,
            'is_confirm' => $order->is_confirm,
            'status' => $order->status,
            'status_name' => $status[$order->status],
            'express' => $order->express,
            'express_no' => $order->express_no,
            'name' => $order->name,
            'mobile' => $order->mobile,
            'address' => $order->address,
            'order_no' => $order->order_no,
            'addtime' => date('Y-m-d H:i', $order->addtime),
            'total_price' => doubleval(sprintf('%.2f', $order->total_price)),
            'express_price' => doubleval(sprintf('%.2f', $order->express_price)),
            'goods_total_price' => doubleval(sprintf('%.2f', doubleval($order->total_price) - doubleval($order->express_price))),
            'pay_price' => $order->pay_price,
            'num' => $num,
            'goods_list' => $goods_list,
            'is_group' => $order->is_group,
            'offline'=>$order->offline,
            'shop'=>$shop,
            'colonel'=>$order->is_group==1?$order->colonel:0,
            'limit_time'=>$limit_time_res,
            'limit_time_ms'=>$limit_time_ms,
        );
        return new \app\hejiang\ApiResponse(0, 'success', $data);
    }

    /**
     * 取消订单
     */
    public function actionRevoke()
    {
        $form = new OrderRevokeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    /**
     * 售后页面
     */
    public function actionRefundPreview()
    {
        $form = new OrderRefundPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }

    /**
     * 售后提交
     * TODO 已废弃 统一在商城售后中处理
     */
    public function actionRefund()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    /**
     * 售后订单详情
     */
    public function actionRefundDetail()
    {
        $form = new OrderRefundDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new \app\hejiang\BaseApiResponse($form->search());
    }
}
