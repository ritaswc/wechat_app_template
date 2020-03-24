<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\gwd;


use app\models\GwdSetting;
use app\modules\mch\controllers\Controller;

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $setting = GwdSetting::find()->where([
                    'store_id' => \Yii::$app->store->id
                ])->one();
                if (!$setting) {
                    $setting = new GwdSetting();
                }
                $setting->status = \Yii::$app->request->post('status');
                $setting->store_id = \Yii::$app->store->id;
                $res = $setting->save();

                if ($res) {
                    return [
                        'code' => 0,
                        'msg' => '保存成功',
                    ];
                }
                return [
                    'code' => 1,
                    'msg' => '保存失败',
                ];
            } else {

            }
        } else {
            $setting = GwdSetting::find()->where([
                'store_id' => \Yii::$app->store->id
            ])->one();

            return $this->render('index', [
                'setting' => $setting
            ]);
        }
    }
}