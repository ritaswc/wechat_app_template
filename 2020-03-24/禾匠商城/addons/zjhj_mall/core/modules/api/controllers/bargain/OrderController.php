<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/19
 * Time: 9:20
 */

namespace app\modules\api\controllers\bargain;


use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\bargain\ActivityForm;
use app\modules\api\models\bargain\BargainSubmitForm;
use app\modules\api\models\bargain\OrderListForm;

class OrderController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'login'=>[
                'class'=>LoginBehavior::className()
            ]
        ]);
    }

    // 砍价提交
    public function actionBargainSubmit()
    {
        $form = new BargainSubmitForm();
        $form->store = $this->store;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }

    // 用户参与砍价
    public function actionBargain()
    {
        $form = new ActivityForm();
        $form->user = \Yii::$app->user->identity;
        $form->store = $this->store;
        $form->order_id = \Yii::$app->request->get('order_id');
        return $form->bargain();
    }

    // 砍价详情页
    public function actionActivity()
    {
        $form = new ActivityForm();
        $form->store = $this->store;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 3;
        $form->page = \Yii::$app->request->get('page');
        return $form->search();
    }

    // 我的砍价
    public function actionOrderList()
    {
        $form = new OrderListForm();
        $form->store = $this->store;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }
}