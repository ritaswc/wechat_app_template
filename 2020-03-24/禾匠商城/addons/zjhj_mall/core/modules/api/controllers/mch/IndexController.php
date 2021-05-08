<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/28
 * Time: 10:53
 */

namespace app\modules\api\controllers\mch;

use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\behaviors\VisitBehavior;
use app\modules\api\controllers\Controller;
use app\modules\api\models\mch\ApplyForm;
use app\modules\api\models\mch\ApplySubmitForm;
use app\modules\api\models\mch\ShopCatForm;
use app\modules\api\models\mch\ShopListForm;
use app\modules\api\models\mch\ShopDataForm;

class IndexController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
            'visit' => [
                'class' => VisitBehavior::className(),
            ],
        ]);
    }

    public function actionApply()
    {
        $form = new ApplyForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    public function actionApplySubmit()
    {
        $form = new ApplySubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    public function actionShop()
    {
        $form = new ShopDataForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        return new BaseApiResponse($form->search());
    }

    public function actionShopList()
    {
        $form = new ShopListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->search());
    }

    public function actionShopCat()
    {
        $form = new ShopCatForm();
        $form->attributes = \Yii::$app->request->get();
        return new BaseApiResponse($form->search());
    }
}
