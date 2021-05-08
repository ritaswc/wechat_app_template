<?php
namespace app\modules\api\controllers\scratch;

use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\controllers\Controller;
use app\modules\api\models\scratch\ScratchForm;
use app\modules\api\models\scratch\ScratchLogForm;
use app\modules\api\models\OrderSubmitForm;

class ScratchController extends Controller
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
        $form = new ScratchForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->index());
    }

    //领取奖品
    public function actionReceive()
    {
        $form = new ScratchLogForm();
        $form->id = \Yii::$app->request->get('id');
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->receive());
    }
    //抽奖规则
    public function actionSetting()
    {
        $form = new ScratchForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->setting());
    }

    //中奖记录
    public function actionPrize()
    {
        $form = new ScratchLogForm();
        $form->page = \Yii::$app->request->get('page');
        $form->limit = 20;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //提交订单
    public function actionSubmit(){
        $form = new OrderSubmitForm();
        $model = \Yii::$app->request->post();
        if ($model['offline'] == 0) {
            $form->scenario = "EXPRESS";
        } else {
            $form->scenario = "OFFLINE";
        }
        $form->mode = 'scratch';
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->version = hj_core_version();
        return new BaseApiResponse($form->convert());
    }
    //
    public function actionLog(){
        $form = new ScratchLogForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->log());
    }    
    //海报
    public function actionQrcode()
    {
        $form = new ScratchForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->qrcode());
    }
}