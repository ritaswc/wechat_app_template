<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/18
 * Time: 13:47
 */

namespace app\modules\mch\controllers;


use app\modules\mch\controllers\bargain\Controller;
use app\modules\mch\models\diy\CatForm;
use app\modules\mch\models\diy\GoodsForm;
use app\modules\mch\models\diy\RubikForm;

class DiyController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionGetCat()
    {
        $form = new CatForm();
        $form->type = \Yii::$app->request->get('type');
        $form->page = \Yii::$app->request->get('page',1);
        $form->limit = 8;
        return $form->search();
    }

    public function actionGetGoods()
    {
        $form = new GoodsForm();
        $form->type = \Yii::$app->request->get('type');
        $form->page = \Yii::$app->request->get('page',1);
        $form->cat = \Yii::$app->request->get('cat',0);
        $form->limit = 8;
        return $form->search();
    }

    public function actionGetRubik()
    {
        $form = new RubikForm();
        $form->id = \Yii::$app->request->get('id');
        return $form->search();
    }
}