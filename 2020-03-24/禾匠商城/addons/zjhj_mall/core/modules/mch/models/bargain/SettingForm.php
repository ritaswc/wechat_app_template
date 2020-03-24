<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/11
 * Time: 9:21
 */

namespace app\modules\mch\models\bargain;


use app\models\BargainSetting;
use app\modules\mch\models\MchModel;

class SettingForm extends MchModel
{
    public $store_id;

    public $is_share;
    public $is_sms;
    public $is_mail;
    public $is_print;
    public $content;
    public $share_title;

    public function rules()
    {
        return [
            [['is_share', 'is_sms', 'is_mail', 'is_print'], 'integer'],
            [['content', 'share_title'], 'trim'],
            [['content'], 'string'],
        ];
    }


    public function search()
    {
        $model = BargainSetting::findOne(['store_id' => $this->store_id]);
        return $model;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $model = BargainSetting::findOne(['store_id' => $this->store_id]);
        if (!$model) {
            $model = new BargainSetting();
            $model->store_id = $this->store_id;
        }
        $model->attributes = $this->attributes;
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '编辑成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}