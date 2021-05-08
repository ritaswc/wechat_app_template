<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/8
 * Time: 15:02
 */

namespace app\modules\api\models\mch;

use app\models\common\CommonFormId;
use app\models\Mch;
use app\modules\api\models\ApiModel;

class SettingSubmitForm extends ApiModel
{
    /** @var  Mch $mch */
    public $mch;

    public $realname;
    public $tel;
    public $name;
    public $province_id;
    public $city_id;
    public $district_id;
    public $address;
    public $mch_common_cat_id;
    public $service_tel;
    public $logo;
    public $header_bg;
    public $wechat_name;
    public $form_id;

    public function rules()
    {
        return [
            [['realname', 'tel', 'wechat_name', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel', 'logo', 'header_bg'], 'trim'],
            [['realname', 'tel', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel', 'logo', 'header_bg'], 'required'],
            ['form_id', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'realname' => '联系人',
            'tel' => '联系电话',
            'name' => '店铺名称',
            'address' => '详细地址',
            'service_tel' => '客服电话',
            'province_id' => '所在地区',
            'city_id' => '所在地区',
            'district_id' => '所在地区',
            'mch_common_cat_id' => '所售类目',
            'logo' => '店铺头像',
            'header_bg' => '店铺背景',
            'wechat_name' => '微信号',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->saveFormId();
        if ($this->mch->review_status != 1) {
            return [
                'code' => 1,
                'msg' => '您的入驻申请尚未通过',
            ];
        }
        $this->mch->attributes = $this->attributes;
        if ($this->mch->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
        return $this->getErrorResponse($this->mch);
    }

    private function saveFormId()
    {
        $res = CommonFormId::save([
            [
                'form_id' => $this->form_id
            ]
        ]);
    }
}
