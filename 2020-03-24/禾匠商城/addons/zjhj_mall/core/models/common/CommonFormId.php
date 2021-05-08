<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common;


use app\models\FormId;

class CommonFormId
{
    public static function save($formIdList)
    {
        if (!is_array($formIdList)) {
            $formIdList = \Yii::$app->serializer->decode($formIdList);
        }
        foreach ($formIdList as $item) {
            if (!empty($item['form_id']) && $item['form_id'] != 'the formId is a mock one') {
                FormId::addFormId([
                    'store_id' => \Yii::$app->store->id,
                    'user_id' => \Yii::$app->user->id,
                    'wechat_open_id' => \Yii::$app->user->identity->wechat_open_id,
                    'form_id' => $item['form_id'],
                    'type' => 'form_id'
                ]);
            }
        }

        return true;
    }

    /**
     * 获取FormId
     * @param $wechatOpenId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getFormId($wechatOpenId)
    {
        $formId = FormId::find()->where([
            'type' => 'prepay_id',
            'wechat_open_id' => $wechatOpenId,
            'is_usable' => 1,
        ])
            ->andWhere(['<', 'send_count', 3])
            ->andWhere(['>', 'addtime', time() - (7 * 24 * 60 * 60)])
            ->one();

        if (!$formId || empty($formId)) {
            $formId = FormId::find()->where([
                'wechat_open_id' => $wechatOpenId,
                'type' => 'form_id',
                'send_count' => 0
            ])->andWhere(['>', 'addtime', time() - (7 * 24 * 60 * 60)])->one();
        }

        return $formId;
    }
}