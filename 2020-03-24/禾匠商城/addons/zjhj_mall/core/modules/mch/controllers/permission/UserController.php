<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\permission;

use app\models\User;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\permission\role\IndexRoleForm;
use app\modules\mch\models\permission\user\DestroyAdminUserForm;
use app\modules\mch\models\permission\user\EditAdminUserForm;
use app\modules\mch\models\permission\user\IndexAdminUserForm;
use app\modules\mch\models\permission\user\StoreAdminUserForm;
use app\modules\mch\models\permission\user\UpdateAdminUserForm;
use app\modules\mch\models\permission\user\UpdatePasswordForm;
use Yii;

class UserController extends Controller
{
    public function actionIndex()
    {
        $model = new IndexAdminUserForm();
        $list = $model->pagination();
        return $this->render('index', [
            'adminUrl' => $list['adminUrl'],
            'list' => $list['list'],
            'pagination' => $list['pagination']
        ]);
    }

    public function actionCreate()
    {
        $model = new IndexRoleForm();
        $list = $model->getList();

        return $this->render('create', ['list' => $list]);
    }

    public function actionStore()
    {
        $data = Yii::$app->request->post();
        $model = new StoreAdminUserForm();
        $model->attributes = $data;

        return $model->store();
    }

    public function actionEdit($id)
    {
        $model = new EditAdminUserForm();
        $model->userId = $id;
        $edit = $model->edit();

        $model = new IndexRoleForm();
        $roleList = $model->getRoleByUser($id);

        return $this->render('edit', ['edit' => $edit, 'roleList' => $roleList]);
    }

    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $model = new UpdateAdminUserForm();
        $model->attributes = $data;
        $model->userId = $id;

        return $model->update();
    }


    public function actionDestroy($id)
    {
        $model = new DestroyAdminUserForm();
        $model->userId = $id;

        return $model->destroy();
    }

    public function actionUpdatePassword()
    {
        $model = new UpdatePasswordForm();
        $model->userId = Yii::$app->request->post('id');
        $model->password = Yii::$app->request->post('password');
        $user = $model->update();

        return $user;
    }
}
