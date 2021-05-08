<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/9
 * Time: 15:53
 */

namespace app\modules\user\controllers\mch;

use app\models\DistrictArr;
use app\models\Express;
use app\models\MchCommonCat;
use app\models\MchPostageRules;
use app\modules\user\behaviors\MchBehavior;
use app\modules\user\behaviors\UserLoginBehavior;
use app\modules\user\controllers\Controller;
use app\modules\user\models\mch\FreeDeliverForm;
use app\modules\user\models\mch\PostageRulesEditForm;
use app\modules\user\models\mch\SettingForm;
use app\modules\user\models\MchDataForm;

class IndexController extends Controller
{
    public function behaviors()
    {
        return [
            'login' => [
                'class' => UserLoginBehavior::className(),
            ],
            'mch' => [
                'class' => MchBehavior::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MchDataForm();
            $form->store_id = $this->store->id;
            $form->mch_id = $this->mch->id;
            $store_data = $form->search();
            return $store_data;
        } else {
            return $this->render('index', [
                'store'=>$this->store
            ]);
        }
    }

    public function actionSetting()
    {
        $mch = $this->mch;
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $form = new SettingForm();
            $form->model = $mch;
            $form->attributes = $model;
            return $form->save();
        }
        return $this->render('setting', [
            'model'=>$mch,
            'province' => DistrictArr::getDistrict(['id' => $mch->province_id]),
            'city' => DistrictArr::getDistrict(['id' => $mch->city_id]),
            'district' => DistrictArr::getDistrict(['id' => $mch->district_id]),
            'mch_common_cat_list' => MchCommonCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all(),
            'setting' => $mch->setting,
            'plugin'=>$mch->plugin
        ]);
    }

    public function actionPostageRules()
    {
        return $this->render('postage-rules', [
            'list' => MchPostageRules::findAll([
                'mch_id' => $this->mch->id,
                'is_delete' => 0,
            ]),
        ]);
    }

    /**
     * 新增、编辑运费规则
     */
    public function actionPostageRulesEdit($id = null)
    {
        $model = MchPostageRules::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new MchPostageRules();
            $model->mch_id = $this->mch->id;
        }
        if (\Yii::$app->request->isPost) {
            $form = new PostageRulesEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->postage_rules = $model;
            return $form->save();
        } else {
            $province = DistrictArr::getRules();
            return $this->render('postage-rules-edit', [
                'model' => $model,
                'express_list' => Express::findAll([
                    'is_delete' => 0,
                ]),
                'province_list' => $province,
            ]);
        }
    }

    /**
     * 删除运费规则
     */
    public function actionPostageRulesDelete($id)
    {
        $model = MchPostageRules::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
            'is_delete' => 0,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }

    public function actionPostageRulesSetEnable($id, $type)
    {
        if ($type == 0) {
            MchPostageRules::updateAll(['is_enable' => 0], ['mch_id' => $this->mch->id, 'is_delete' => 0, 'id' => $id]);
        }
        if ($type == 1) {
            MchPostageRules::updateAll(['is_enable' => 0], ['mch_id' => $this->mch->id, 'is_delete' => 0]);
            MchPostageRules::updateAll(['is_enable' => 1], ['mch_id' => $this->mch->id, 'is_delete' => 0, 'id' => $id]);
        }
        $this->redirect(\Yii::$app->request->referrer)->send();
    }

    // 包邮规则
    public function actionFreeDeliverRules()
    {
        $form = new FreeDeliverForm();
        $form->store = $this->store;
        $form->mch = $this->mch;
        if(\Yii::$app->request->isPost){
            $form->attributes = \Yii::$app->request->post('data');
            return $form->save();
        }else{
            $res = $form->search();
            return $this->render('free-deliver-rules', [
                'ruleList' => \Yii::$app->serializer->encode($res['ruleList']),
                'province_list' => \Yii::$app->serializer->encode($res['province_list']),
                'is_enable' => $res['is_enable'],
                'total_price' => $res['total_price']
            ]);
        }
    }
}
