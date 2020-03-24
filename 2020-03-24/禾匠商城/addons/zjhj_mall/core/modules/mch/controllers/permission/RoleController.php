<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\permission;

use app\modules\mch\controllers\Controller;
use app\modules\mch\models\permission\permission\IndexPermissionForm;
use app\modules\mch\models\permission\role\DestroyRoleForm;
use app\modules\mch\models\permission\role\IndexRoleForm;
use app\modules\mch\models\permission\role\EditRoleForm;
use app\modules\mch\models\permission\role\StoreRoleForm;
use app\modules\mch\models\permission\role\UpdateRoleForm;
use Yii;

class RoleController extends Controller
{
    public function actionIndex()
    {
        $model = new IndexRoleForm();
        $list = $model->pagination();

        return $this->render('index', ['list' => $list['list'], 'pagination' => $list['pagination']]);
    }

    public function actionCreate()
    {
        $model = new IndexPermissionForm();
        $list = $model->getList();
        $list = Yii::$app->serializer->encode($list);

        return $this->render('create', ['list' => $list]);
    }

    public function actionStore()
    {
        $data = Yii::$app->request->post();
        $model = new StoreRoleForm();
        $model->attributes = $data;

        return $model->store();
    }

    public function actionEdit($id)
    {
        $model = new EditRoleForm();
        $model->roleId = $id;
        $edit = $model->edit();

        $model = new IndexPermissionForm();
        $permissions = $model->getPermissionMenuByUser($id);

        return $this->render('edit', ['edit' => $edit, 'permissions' => $permissions]);
    }

    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $model = new UpdateRoleForm();
        $model->attributes = $data;
        $model->roleId = $id;
        $updated = $model->update();

        return $updated;
    }


    public function actionDestroy($id)
    {
        $model = new DestroyRoleForm();
        $model->roleId = $id;

        $destroyed = $model->destroy();

        return $destroyed;
    }
}
