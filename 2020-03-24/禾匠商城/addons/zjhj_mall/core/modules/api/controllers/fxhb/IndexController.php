<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 16:08
 */

namespace app\modules\api\controllers\fxhb;

use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\controllers\Controller;
use app\modules\api\models\fxhb\DetailForm;
use app\modules\api\models\fxhb\DetailSubmitForm;
use app\modules\api\models\fxhb\OpenForm;
use app\modules\api\models\fxhb\OpenSubmitForm;

class IndexController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    //拆新红包页面
    public function actionOpen()
    {
        $form = new OpenForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //拆新红包
    public function actionOpenSubmit()
    {
        $form = new OpenSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //红包详情页面
    public function actionDetail()
    {
        $form = new DetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //帮助拆红包
    public function actionDetailSubmit()
    {
        $form = new DetailSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }
}
