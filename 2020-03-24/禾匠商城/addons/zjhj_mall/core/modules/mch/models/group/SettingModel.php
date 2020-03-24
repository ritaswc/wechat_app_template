<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 9:04
 */

namespace app\modules\mch\models\group;

use app\modules\mch\models\MchModel;

/**
 * @property \app\models\PtSetting $setting
 */
class SettingModel extends MchModel
{
    public $store_id;
    public $setting;


    public $is_share;
    public $is_sms;
    public $is_print;
    public $is_mail;
    public $is_area;

    public function rules()
    {
        return [
            [['is_share','is_sms','is_print','is_mail','is_area'],'integer'],
            [['is_share','is_sms','is_print','is_mail','is_area'],'default','value'=>0],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->setting->attributes = $this->attributes;
        if ($this->setting->save()) {
            return [
                'code'=>0,
                'msg'=>'保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->setting);
        }
    }
}
