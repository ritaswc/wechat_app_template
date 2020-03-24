<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\gwd;

use app\modules\mch\controllers\Controller;
use app\modules\mch\models\gwd\BuyListForm;

class BuyListController extends Controller
{
    public function actionIndex()
    {
        $form = new BuyListForm();
        $form->orderType = \Yii::$app->request->get('orderType');
        $res = $form->getList();

        return $this->render('index', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    public function actionEdit()
    {
        $form = new BuyListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getOrderList();

        return $this->render('edit', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    public function actionStore()
    {
        $form = new BuyListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->store();

        return $res;
    }

    public function actionDestroy()
    {
        $form = new BuyListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->destroy();

        return $res;
    }

    public function actionBatch()
    {
        $form = new BuyListForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->batch();

        return $res;
    }

    public function actionUpdateOrderStatus()
    {
        $form = new BuyListForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->updateOrderStatus();

        return $res;
    }
}