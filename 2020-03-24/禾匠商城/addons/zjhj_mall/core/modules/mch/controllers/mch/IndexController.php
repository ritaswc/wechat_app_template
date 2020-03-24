<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/28
 * Time: 10:22
 */

namespace app\modules\mch\controllers\mch;

use app\hejiang\ApiCode;
use app\models\DistrictArr;
use app\models\Mch;
use app\models\MchCommonCat;
use app\models\User;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\mch\CashConfirmForm;
use app\modules\mch\models\mch\CashListForm;
use app\modules\mch\models\mch\CommonCatEditForm;
use app\modules\mch\models\mch\MchAddForm;
use app\modules\mch\models\mch\MchEditForm;
use app\modules\mch\models\mch\MchListForm;
use app\modules\mch\models\mch\MchSettingForm;
use app\modules\mch\models\mch\OneMchSettingForm;
use app\modules\mch\models\mch\ReportFormsForm;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $form = new MchListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->platform = \Yii::$app->request->get('platform');
        $form->review_status = 1;
        $form->store_id = $this->store->id;
        $res = $form->search();
        return $this->render('index', [
            'adminUrl' => $res['data']['adminUrl'],
            'list' => $res['data']['list'],
            'pagination' => $res['data']['pagination'],
            'get' => \Yii::$app->request->get(),
        ]);
    }

    public function actionApply($review_status = 0)
    {
        $form = new MchListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->review_status = $review_status;
        $res = $form->search();
        return $this->render('apply', [
            'list' => $res['data']['list'],
            'pagination' => $res['data']['pagination'],
            'get' => \Yii::$app->request->get(),
        ]);
    }

    public function actionEdit($id)
    {
        $model = Mch::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
            return;
        }
        if (\Yii::$app->request->isPost) {
            $form = new MchEditForm();
            $form->model = $model;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            foreach ($model as $index => $value) {
                $model[$index] = str_replace("\"", "&quot;", $value);
            }
            return $this->render('edit', [
                'model' => $model,
                'province' => DistrictArr::getDistrict(['id' => $model->province_id]),
                'city' => DistrictArr::getDistrict(['id' => $model->city_id]),
                'district' => DistrictArr::getDistrict(['id' => $model->district_id]),
                'mch_common_cat_list' => MchCommonCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all(),
            ]);
        }
    }

    public function actionCommonCat()
    {
        return $this->render('common-cat', [
            'list' => MchCommonCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('sort')->all(),
        ]);
    }

    public function actionCommonCatEdit($id = null)
    {
        $model = MchCommonCat::findOne(['id' => $id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$model) {
            $model = new MchCommonCat();
        }

        if (\Yii::$app->request->isPost) {
            $form = new CommonCatEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->model = $model;
            return $form->save();
        } else {
            return $this->render('common-cat-edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionCommonCatDelete($id)
    {
        $model = MchCommonCat::findOne(['id' => $id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '删除成功'
        ];
    }

    public function actionCash()
    {
        $get = \Yii::$app->request->get();
        if (!isset($get['status']) || $get['status'] === null || $get['status'] === '') {
            $get['status'] = -1;
        }

        $form = new CashListForm();
        $form->attributes = $get;
        $form->store_id = $this->store->id;
        $res = $form->search();
        return $this->render('cash', [
            'get' => $get,
            'list' => isset($res['data']['list']) ? $res['data']['list'] : [],
            'pagination' => isset($res['data']['pagination']) ? $res['data']['pagination'] : null,
        ]);
    }

    public function actionCashSubmit()
    {
        $form = new CashConfirmForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->save();
    }


    /**
     * 店铺状态开头
     * @param $id 多商户 ID
     * @param $status 状态 1 或 0
     * @param $type 要改状态的字段
     * @return array
     */
    public function actionSetStatus($id, $status, $type)
    {
        $mch = Mch::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        if (!$mch) {
            return [
                'code' => 1,
                'msg' => '店铺不存在。',
            ];
        }
        $mch->$type = $status ? 1 : 0;
        $mch->save();
        return [
            'code' => 0,
            'msg' => '保存成功。',
        ];
    }

    public function actionSetting()
    {
        $form = new MchSettingForm();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            return $this->render('setting', [
                'model' => $form->search(),
            ]);
        }
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->isPost) {
            $form = new MchAddForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->save();
        } else {
            if (\Yii::$app->request->isAjax) {
                $keyword = trim(\Yii::$app->request->get('keyword'));
                $query = User::find()
                    ->alias('u')
                    ->leftJoin(['m' => Mch::tableName()], 'm.user_id=u.id')->where([
                            'AND',
                            ['m.id' => null],
                            ['u.store_id' => $this->store->id,],
                        ])->OrWhere([
                            'AND',
                            ['m.is_delete' => 1],
                            ['u.store_id' => $this->store->id,],
                        ])->OrWhere([
                            'AND',
                            ['m.review_status' => 2],
                            ['u.store_id' => $this->store->id,],
                        ]);
                if ($keyword) {
                    $query->andWhere(['LIKE', 'u.nickname', $keyword]);
                }
                $list = $query->select('u.id,u.nickname,u.avatar_url')->asArray()
                    ->limit(20)->orderBy('u.nickname')->all();
                return [
                    'code' => 0,
                    'data' => $list,
                ];
            } else {
                return $this->render('add', [
                    'mch_common_cat_list' => MchCommonCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all(),
                ]);
            }
        }
    }

    public function actionMchSetting($id = null)
    {
        $mch = Mch::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$mch) {
            \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
            return;
        }
        if (\Yii::$app->request->isPost) {
            $form = new OneMchSettingForm();
            $form->attributes = \Yii::$app->request->post('model');
            $form->store = $this->store;
            $form->mch_id = $mch->id;
            return $form->save();
        }
        return $this->render('mch-setting', [
            'mch' => $mch,
            'setting' => $mch->plugin
        ]);
    }

    /**
     * 销售报表
     */
    public function actionReportForms()
    {
        $model = new ReportFormsForm();
        $excelFields = $model->excelFields();
        $model->attributes = \Yii::$app->request->get();
        $model->attributes = \Yii::$app->request->post();
        $list = $model->search();

        return $this->render('report-forms', [
            'data' => $list,
            'exportList' => \Yii::$app->serializer->encode($excelFields)
        ]);
    }

    public function actionMchDel()
    {
        $form = new MchListForm();
        $form->mch_id = \Yii::$app->request->get('id');
        $res = $form->delete();

        return $res;
    }
}
