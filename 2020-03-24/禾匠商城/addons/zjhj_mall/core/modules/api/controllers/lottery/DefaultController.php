<?php
namespace app\modules\api\controllers\lottery;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\lottery\IndexForm;
use app\modules\api\models\ShareQrcodeForm;
use app\modules\api\models\lottery\LotteryLogForm;
use app\modules\api\models\OrderSubmitForm;

class DefaultController extends Controller
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
        $form = new IndexForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $res = $form->search();
        return $res;
    }

    public function actionPrize()
    {
        $form = new LotteryLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->search());
    }

    public function actionLuckyCode()
    {
        $form = new LotteryLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->luckyCode());
    }

    public function actionClerk()
    {
        $form = new LotteryLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->clerk());
    }

    public function actionDetail()
    {
        $form = new LotteryLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save()); 
    }

    public function actionGoods()
    {
        $form = new IndexForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->goods()); 
    }


    //海报
    public function actionQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        $form->type = 7;
        if (!\Yii::$app->user->isGuest) {
            $form->user = \Yii::$app->user->identity;
            $form->user_id = \Yii::$app->user->id;
        }
        return new BaseApiResponse($form->search());
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
        $form->mode = 'lottery';
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->version = hj_core_version();
        return new BaseApiResponse($form->convert());
    }

    public function actionSetting(){
        $form = new indexForm();
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->setting());
    }
}
