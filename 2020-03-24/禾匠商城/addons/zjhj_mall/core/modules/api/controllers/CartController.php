<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/27
 * Time: 1:06
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\AddCartForm;
use app\modules\api\models\CartDeleteForm;
use app\modules\api\models\CartListForm;

class CartController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    public function actionList()
    {
        $form = new CartListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    public function actionAddCart()
    {
        if (\Yii::$app->request->isPost) {
            $form = new AddCartForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->user_id = \Yii::$app->user->id;
            return new BaseApiResponse($form->save());
        }
    }

    public function actionDelete()
    {
        $form = new CartDeleteForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    public function actionCartEdit()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CartListForm();
            $form->store_id = $this->store->id;
            $form->user_id = \Yii::$app->user->id;
            $form->list = \Yii::$app->request->post('list');
            $form->mch_list = \Yii::$app->request->post('mch_list');
            return new BaseApiResponse($form->save());
        }
    }
}
