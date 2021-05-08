<?php
namespace app\modules\api\controllers\step;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\modules\api\models\step\IndexForm;
use app\modules\api\models\step\StepLogForm;
use app\modules\api\models\step\GoodsForm;
use app\modules\api\models\OrderSubmitForm;
use app\modules\api\models\ShareQrcodeForm;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $form = new IndexForm();
        $form->attributes = \Yii::$app->request->post();
        $form->parent_id = \Yii::$app->request->post('user_id');
        $form->wechat_app = $this->wechat_app;
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->index());
    }
    public function actionSetting()
    {
        $form = new IndexForm();
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->setting());
    }

    public function actionRanking()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        $form->page = \Yii::$app->request->get('page');
        $form->status = \Yii::$app->request->get('status');
        return new BaseApiResponse($form->ranking());
    }
    public function actionPicList()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        $form->page = \Yii::$app->request->get('page');
        return new BaseApiResponse($form->picList());
    }

    public function actionQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store_id;
        $form->type = 8;
        if (!\Yii::$app->user->isGuest) {
            $form->user = \Yii::$app->user->identity;
            $form->user_id = \Yii::$app->user->id;
        }
        return new BaseApiResponse($form->search());
    }

    public function actionLog()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->page = \Yii::$app->request->get('page');
        $form->status = \Yii::$app->request->get('status');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->search());
    }

    public function actionGoods()
    {
        $form = new IndexForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->goods_id = \Yii::$app->request->get('goods_id');
        $form->parent_id = \Yii::$app->request->get('user_id');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->goods());
    }

    public function actionConvert()
    {
        $form = new IndexForm();
        $form->attributes = \Yii::$app->request->post();
        $form->wechat_app = $this->wechat_app;
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save());
    }

    public function actionActivity()
    {
        $form = new IndexForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->user = \Yii::$app->user->identity;
        $form->parent_id = \Yii::$app->request->post('user_id');
        $form->wechat_app = $this->wechat_app;
        return new BaseApiResponse($form->activity());
    }

    public function actionActivityJoin()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->activity_id = \Yii::$app->request->get('activity_id');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->activityJoin());
    }

    public function actionActivityDetail()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->activity_id = \Yii::$app->request->get('activity_id');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->activityDetail());
    }

    public function actionActivityLog()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->page = \Yii::$app->request->get('page');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->activityLog());
    }

    public function actionActivitySubmit()
    {
        $form = new IndexForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->wechat_app = $this->wechat_app;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->activitySubmit());
    }

    public function actionInviteDetail()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store_id;
        $form->page = \Yii::$app->request->get('page');
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->inviteDetail());
    }
    public function actionRemind()
    {
        $form = new StepLogForm();
        $form->attributes = \Yii::$app->request->post();
        $form->remind = \Yii::$app->request->get('remind');
        $form->user = \Yii::$app->user->identity;
        $form->store_id = $this->store_id;
        return new BaseApiResponse($form->remind());
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
        $form->mode = 'step';
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->version = hj_core_version();
        return new BaseApiResponse($form->convert());
    }
}
