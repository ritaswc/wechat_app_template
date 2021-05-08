<?php

/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/11/22
 * Time: 14:58
 */

namespace app\modules\mch\controllers\book;

use app\models\YySetting;
use app\models\Option;

class IndexController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['mch/book/goods/index']));
    }

    /**
     * @return string
     *
     */
    public function actionSetting()
    {
        $setting = YySetting::findOne(['store_id' => $this->store->id]);
        if (!$setting) {
            $setting = new YySetting();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            if ($setting->isNewRecord) {
                $setting->store_id = $this->store->id;
            }
            $setting->attributes = $model;

            $payment = \Yii::$app->request->post('payment');
            if (!$payment) {
                $payment['wechat'] = 1;
            }
            $payment = \Yii::$app->serializer->encode($payment);
            $list = [
                [
                    'name' => 'yy_payment',
                    'value' => $payment
                ],
            ];
            Option::setList($list,  $this->store->id, 'admin');

            if ($setting->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return [
                    'code' => 0,
                    'msg' => '保存失败，请重试',
                ];
            }
        }

        $option = Option::getList([
            'yy_payment',
        ], $this->store->id, 'admin');
        if (!$option['yy_payment']) {
            $option['yy_payment']['wechat'] = 1;
        } else {
            $option['yy_payment'] = \Yii::$app->serializer->decode($option['yy_payment']);
            /*if (!is_array($option['yy_payment'])) {
                 $option['yy_payment']['wechat'] = 1;
            } */
        }
        $yyPayment = $option['yy_payment'];

        return $this->render('setting', [
            'yyPayment' => $yyPayment,
            'setting' => $setting,
        ]);
    }
}
