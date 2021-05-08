<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/19
 * Time: 11:53
 */

namespace app\modules\admin\controllers;

use app\models\AdminPermission;
use app\models\common\admin\store\CommonStore;
use app\models\Option;
use app\models\UserCenterForm;
use app\models\We7Db;
use app\models\We7UserAuth;
use app\modules\mch\models\CopyrightForm;
use app\modules\mch\models\We7AuthForm;
use yii\data\Pagination;
use yii\db\Query;

class AdminController extends Controller
{
    public function actionAuth($keyword = null)
    {
        if (\Yii::$app->request->isPost) {
            $form = new We7AuthForm();
            if (\Yii::$app->request->post('multiple')) {
                $form->setScenario('multiple');
            } else {
                $form->setScenario('one');
            }
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            $query = new Query();
            $query->select('u.uid,u.username,u.joindate,ua.auth')
                ->from(['u' => We7Db::getTableName('users')])
                ->leftJoin(['ua' => We7UserAuth::tableName()], 'u.uid=ua.we7_user_id');
            if ($keyword) {
                $query->andWhere([
                    'OR',
                    ['LIKE', 'u.uid', $keyword],
                    ['LIKE', 'u.username', $keyword],
                ]);
            }
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count,]);
            $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('u.uid DESC')->all();
            $all_permission = $this->getAllPermission();
            $default_all_permission = Option::get('we7_default_all_permission');
            foreach ($list as $i => &$item) {
                if ($item['uid'] == 1) {
                    $item['auth'] = $all_permission;
                } else {
                    $item['auth'] = $item['auth'] === null ? ($default_all_permission ? $all_permission : null) : json_decode($item['auth'], true);
                }
            }
            return $this->render('auth', [
                'list' => $list,
                'permission_list' => AdminPermission::getList(),
                'pagination' => $pagination,
                'we7_default_all_permission' => Option::get('we7_default_all_permission'),
            ]);
        }
    }

    public function actionCopyright($id = null, $url = null)
    {
        if (!$id) {
            $store_id = $this->store->id;
        } else {
            $store_id = $id;
        }
        $model = new UserCenterForm();
        $model->store_id = $store_id;
        $res = $model->getData();
        $data = $res['data'];
        if (\Yii::$app->request->isPost) {
            $form = new CopyrightForm();
            $form->data = $data;
            $form->store_id = $store_id;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            foreach ($data as $index => $value) {
                $data[$index] = str_replace("\"", "&quot;", $value);
            }
            return $this->render('copyright', [
                'data' => $data,
                'url' => $url
            ]);
        }
    }

    public function actionCopyrightList($keyword = null)
    {
        $common = new CommonStore();
        $common->keyword = $keyword;
        $common->is_ind = true;
        $res = $common->storeList();

        return $this->render('store', ['list' => $res['list'], 'pagination' => $res['pagination']]);
    }

    public function actionDefaultAllPermission()
    {
        $we7_default_all_permission = \Yii::$app->request->post('we7_default_all_permission');
        Option::set('we7_default_all_permission', $we7_default_all_permission ? true : false);
        return [
            'code' => 0,
            'msg' => '保存成功。',
        ];
    }
}
