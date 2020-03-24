<?php
namespace app\modules\api\controllers\pond;

use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\controllers\Controller;
use app\modules\api\models\pond\PondForm;
use app\modules\api\models\pond\PondLogForm;
use app\modules\api\models\OrderSubmitForm;

class PondController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    //奖品列表
    public function actionIndex()
    {
        $form = new PondForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->index());
    }
    //立即抽奖
    public function actionLottery()
    {
        $form = new PondForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $data = $form->lottery();
        if ($data['code']==0) {
            $logform = new PondLogForm();
            $logform->store_id = $this->store->id;
            $logform->id = $data['data']->p_id;
            $logform->user_id = \Yii::$app->user->id;
            $logform->send();
        }
        return new BaseApiResponse($data);
    }
    //中奖记录
    public function actionPrize()
    {
        $form = new PondLogForm();
        $form->page = \Yii::$app->request->get('page');
        $form->limit = 20;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }
    //兑换商品
    public function actionSend()
    {
        $form = new PondLogForm();
        $form->store_id = $this->store->id;
        $form->id = \Yii::$app->request->get('id');
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->send());
    }
    //抽奖规则
    public function actionSetting()
    {
        $form = new PondForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->setting());
    }
    //提交订单
    public function actionSubmit()
    {
        $form = new OrderSubmitForm();
        $model = \Yii::$app->request->post();
        if ($model['offline'] == 0) {
            $form->scenario = "EXPRESS";
        } else {
            $form->scenario = "OFFLINE";
        }
        $form->mode = 'pond';
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->version = hj_core_version();
        return new BaseApiResponse($form->convert());
    }
    //海报
    public function actionQrcode()
    {
        $form = new PondForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->qrcode());
    }
}
