<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 18:40
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\CouponIndexForm;
use app\modules\api\models\CouponShareSendForm;

class CouponController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    //我的优惠券列表
    public function actionIndex()
    {
        $form = new CouponIndexForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //分享页面送优惠券
    public function actionShareSend()
    {
        $form = new CouponShareSendForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //领取优惠券
    public function actionReceive()
    {
        $form = new CouponShareSendForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->id = \Yii::$app->request->get('id');
        return new BaseApiResponse($form->send());
    }
    //优惠卷详情
    public function actionDetail()
    {
        $form = new CouponIndexForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->id = \Yii::$app->request->get('user_conpon_id');
        $form->coupon_id = \Yii::$app->request->get('coupon_id');
        return new BaseApiResponse($form->detail());
    }
}
