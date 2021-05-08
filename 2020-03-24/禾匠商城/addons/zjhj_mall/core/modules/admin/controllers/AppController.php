<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/6
 * Time: 10:20
 */

namespace app\modules\admin\controllers;

use app\models\Admin;
use app\models\common\admin\log\CommonActionLog;
use app\models\common\admin\store\CommonAppDisabled;
use app\models\Store;
use app\modules\admin\models\app\AppDisabledForm;
use app\modules\admin\models\AppEditForm;
use app\modules\admin\models\RemovalForm;
use yii\data\Pagination;
use Yii;

class AppController extends Controller
{

    //我的小程序商城
    public function actionIndex()
    {
        $query = Store::find()->where([
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
            'is_recycle' => 0,
        ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id DESC')->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
            'app_max_count' => \Yii::$app->admin->identity->app_max_count,
            'app_count' => Store::find()->where([
                'admin_id' => \Yii::$app->admin->id,
                'is_delete' => 0,
            ])->count(),
        ]);
    }

    //子账户的小程序商城
    public function actionOtherApp($keyword = null)
    {
        $query = Store::find()->alias('s')->where([
            'AND',
            ['!=', 's.admin_id', \Yii::$app->admin->id],
            ['s.is_delete' => 0],
            ['a.is_delete' => 0],
        ])->leftJoin(['a' => Admin::tableName()], 's.admin_id=a.id');
        ;
        if ($keyword = trim($keyword)) {
            $query->andWhere([
                'OR',
                ['LIKE', 's.name', $keyword],
                ['LIKE', 'a.username', $keyword],
            ]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id DESC')
            ->select('s.*,a.username')->asArray()->all();
        return $this->render('other-app', [
            'list' => $list,
            'keyword' => $keyword,
            'pagination' => $pagination,
        ]);
    }

    //新增、编辑小程序
    public function actionEdit()
    {
        $form = new AppEditForm();
        $form->attributes = \Yii::$app->request->post();
        $form->admin_id = \Yii::$app->admin->id;
        return $form->save();
    }

    //进入小程序后台
    public function actionEntry($id)
    {
        $condition = [
            'id' => $id,
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ];
        if (\Yii::$app->admin->id == 1) {
            unset($condition['admin_id']);
        }
        $store = Store::findOne($condition);
        if (!$store) {
            \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
            return;
        }
        \Yii::$app->session->set('store_id', $store->id);
        CommonActionLog::storeActionLog('', 'login', 0, [], \Yii::$app->admin->id);
        \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['mch/store/index']))->send();
    }

    //删除小程序商城
    public function actionDelete($id)
    {
        $condition = [
            'id' => $id,
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ];
        if (\Yii::$app->admin->id == 1) {
            unset($condition['admin_id']);
        }
        $store = Store::findOne($condition);
        if ($store) {
            $store->is_delete = 1;
            $store->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //小程序回收站
    public function actionRecycle()
    {
        $query = Store::find()->where([
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
            'is_recycle' => 1,
        ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id DESC')->all();
        return $this->render('recycle', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionSetRecycle($id, $action)
    {
        $condition = [
            'id' => $id,
            'admin_id' => \Yii::$app->admin->id,
            'is_delete' => 0,
        ];
        if (\Yii::$app->admin->id == 1) {
            unset($condition['admin_id']);
        }
        $store = Store::findOne($condition);
        if ($store) {
            $store->is_recycle = $action ? 1 : 0;
            $store->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    public function actionOtherUser($keyword = null)
    {
        $query = Admin::find()->where(['is_delete' => 0]);
        if (trim($keyword)) {
            $query->andWhere(['like', 'username', $keyword]);
        }
        $list = $query->limit(10)->asArray()->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list
            ]
        ];
    }

    public function actionRemoval()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new RemovalForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->save();
        }
    }

    /**
     * 小程序商城 禁用|解禁
     * @return array|mixed
     */
    public function actionDisabled()
    {
        $data = Yii::$app->request->get();
        $common = new CommonAppDisabled();
        $common->attributes = $data;
        $disabled = $common->disabled();

        return $disabled;
    }
}
