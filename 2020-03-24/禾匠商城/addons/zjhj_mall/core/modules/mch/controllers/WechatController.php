<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers;

use app\models\FxhbSetting;
use app\models\Option;
use app\models\WechatTemplateMessage;
use app\models\YySetting;
use app\modules\mch\models\group\NoticeForm;
use app\modules\mch\models\WechatSettingForm;
use yii\db\Exception;

class WechatController extends Controller
{
    public function actionMpConfig()
    {
        if (\Yii::$app->request->isPost) {
            $form = new WechatSettingForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $this->wechat_app;
            return $form->save();
        } else {
            return $this->render('mp-config', [
                'model' => $this->wechat_app,
            ]);
        }
    }


    /**
     * 微信所有模块消息配置
     * @return string
     * @throws Exception
     */
    public function actionTemplateMsg()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                //商城
                $store = WechatTemplateMessage::findOne(['store_id' => $this->store->id]);
                if (!$store) {
                    $store = new WechatTemplateMessage();
                }
                $store->store_id = $this->store->id;
                $store->attributes = $data;
                $store->save();

                //分销
                Option::setList([
                    [
                        'name' => 'cash_success_tpl',
                        'value' => $data['cash_success_tpl'],
                    ],
                    [
                        'name' => 'cash_fail_tpl',
                        'value' => $data['cash_fail_tpl'],
                    ],
                    [
                        'name' => 'apply_tpl',
                        'value' => $data['apply_tpl'],
                    ],
                ], $this->store->id, 'share');

                // 活动通用
                Option::setList([
                    [
                        'name' => 'success_tpl',
                        'value' => $data['activity_success_tpl'],
                    ],
                    [
                        'name' => 'refund_tpl',
                        'value' => $data['activity_refund_tpl'],
                    ],
                ], $this->store->id, 'activity');


                // 拼团
                $pintuan = new NoticeForm();
                $pintuan->pintuan_success_notice = $data['pintuan_success_notice'];
                $pintuan->pintuan_fail_notice = $data['pintuan_fail_notice'];
                $pintuan->pintuan_refund_notice = $data['pintuan_refund_notice'];
                $pintuan->store_id = $this->store->id;
                $pintuan->save();

                // 预约
                $setting = YySetting::findOne(['store_id' => $this->store->id]);
                if (!$setting) {
                    $setting = new YySetting();
                    $setting->store_id = $this->store->id;
                    $setting->cat = 0;
                }
                $setting->success_notice = $data['yy_success_notice'];
                $setting->refund_notice = $data['yy_refund_notice'];
                $setting->save();

                // 多商户
                Option::set('mch_tpl_msg', [
                    'apply' => \Yii::$app->request->post('mch_tpl_1', ''),
                    'order' => \Yii::$app->request->post('mch_tpl_2', ''),
                ], $this->store->id);

                //抽奖
                Option::set('lottery_success_notice', $data['lottery_success_notice'], $this->store->id, 'lottery');

                $fxhbTplMsg = FxhbSetting::findOne(['store_id' => $this->store->id]);
                if (!$fxhbTplMsg) {
                    $fxhbTplMsg = new FxhbSetting();
                }
                $fxhbTplMsg->tpl_msg_id = $data['tpl_msg_id'];
                $fxhbTplMsg->store_id = $this->store->id;
                $fxhbTplMsg->save();

                $transaction->commit();

                return [
                    'code' => 0,
                    'msg' => '保存成功'
                ];

            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e);
            }

        } else {
            $storeTplMsg = WechatTemplateMessage::find()->where(['store_id' => $this->store->id])->asArray()->one();
            $shareTplMsg = Option::getList('cash_success_tpl,cash_fail_tpl,apply_tpl', $this->store->id, 'share', '');
            $activityTplMsg = Option::getList('success_tpl,refund_tpl', $this->store->id, 'activity', '');

            $form = new NoticeForm();
            $form->store_id = $this->store->id;
            $ptTplMsg = $form->getModel();

            $bookTplMsg = YySetting::find()->where(['store_id' => $this->store->id])->asArray()->one();
            $mchTplMsg = Option::get('mch_tpl_msg', $this->store->id, ['apply' => '', 'order' => '']);
            $fxhbTplMsg = FxhbSetting::find()->where(['store_id' => $this->store->id])->asArray()->one();
            $lotteryTplMsg = Option::getList('lottery_success_notice', $this->store->id, 'lottery', '');

            // 当前用户插件权限
            $userAuth = $this->getUserAuth();
            $storeTplMsg['activity_success_tpl'] = $activityTplMsg['success_tpl'];
            $storeTplMsg['activity_refund_tpl'] = $activityTplMsg['refund_tpl'];
            $storeTplMsg['apply'] = $mchTplMsg['apply'];
            $storeTplMsg['account_change_tpl'] = $fxhbTplMsg['tpl_msg_id'];

            $tplMsg = [
                'store' => $storeTplMsg,
                'share' => $shareTplMsg,
                'pintuan' => $ptTplMsg,
                'book' => $bookTplMsg,
                'mch' => $mchTplMsg,
                'lottery' => $lotteryTplMsg,
            ];

            foreach ($tplMsg as $k => $item) {
                // $k === store 商城的模版消息不需要判断权限
                if (in_array($k, $userAuth) || $k === 'store') {
                    $tplMsg[$k]['is_show'] = true;
                    continue;
                }

                $tplMsg[$k]['is_show'] = false;
            }

            return $this->render('template-msg', [
                'tplMsg' => $tplMsg,
            ]);
        }
    }
}