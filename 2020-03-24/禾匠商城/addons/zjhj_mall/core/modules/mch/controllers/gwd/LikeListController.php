<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\gwd;

use app\modules\mch\controllers\Controller;
use app\modules\mch\models\gwd\LikeListForm;

class LikeListController extends Controller
{
    public function actionIndex()
    {
        $form = new LikeListForm();
        $form->type = 0;
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getList();

        return $this->render('index', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    public function actionEdit()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getDetail();

        return $this->render('edit', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    public function actionAddGood()
    {
        $form = new LikeListForm();
        $form->id = \Yii::$app->request->post('good_id');
        $res = $form->addGood();

        return $res;
    }

    public function actionDestroyUser()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->destroyUser();

        return $res;
    }

    public function actionDestroyGood()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->destroyGood();

        return $res;
    }

    public function actionGoodsSearch()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->goods();

        return $res;
    }

    public function actionGoodIds()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->goodIds();

        return $res;
    }

    public function actionUser()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getUsers();

        return $res;
    }

    public function actionUserList()
    {
        $form = new LikeListForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getUserList();

        return $res;
    }

    public function actionAddUser()
    {
        $form = new LikeListForm();
        $form->id = \Yii::$app->request->post('user_id');
        $form->like_id = \Yii::$app->request->post('like_id');
        $res = $form->addUser();

        return $res;
    }

    public function actionAllUser()
    {
        $form = new LikeListForm();
        $form->id = \Yii::$app->request->get('like_id');
        $res = $form->allUser();

        return $res;
    }

    public function actionLikeUsers()
    {
        $form = new LikeListForm();
        $form->id = \Yii::$app->request->get('id');
        $res = $form->getLikeUsers();

        return $res;
    }
}