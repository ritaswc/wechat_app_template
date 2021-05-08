<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/12
 * Time: 16:32
 */

namespace app\modules\api\models;

use app\models\Address;
use yii\helpers\Html;

class AddWechatAddressForm extends ApiModel
{
    public $user_id;
    public $store_id;

    public $national_code;
    public $name;
    public $mobile;
    public $detail;

    public $province_name;
    public $city_name;
    public $county_name;

    public $province_id;
    public $city_id;
    public $district_id;

    public function rules()
    {
        return [
            ['national_code', 'safe'],
            [['name', 'mobile', 'detail'], 'required'],
            [['province_name', 'city_name', 'county_name',], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $wechat_district_form = new WechatDistrictForm();
        $wechat_district_form->national_code = $this->national_code;
        $wechat_district_form->province_name = $this->province_name;
        $wechat_district_form->city_name = $this->city_name;
        $wechat_district_form->county_name = $this->county_name;
        $district_data = $wechat_district_form->search();
        if ($district_data['code'] != 0) {
//            if ($district_data['code'] == 2){
//
//            }
                return $district_data;
        }
        $this->province_id = $district_data['data']['district']['province']['id'];
        $this->city_id = $district_data['data']['district']['city']['id'];
        $this->district_id = $district_data['data']['district']['district']['id'];

        $exist = Address::findOne([
            'user_id' => $this->user_id,
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'detail' => $this->detail,
        ]);
        if ($exist) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $exist->attributes,
            ];
        }
        $model = new Address();
        $model->store_id = $this->store_id;
        $model->user_id = $this->user_id;
        $model->name = Html::encode($this->name);
        $model->mobile = Html::encode($this->mobile);
        $model->detail = Html::encode($this->detail);
        $model->province_id = $district_data['data']['district']['province']['id'];
        $model->province = $district_data['data']['district']['province']['name'];

        $model->city_id = $district_data['data']['district']['city']['id'];
        $model->city = $district_data['data']['district']['city']['name'];

        $model->district_id = $district_data['data']['district']['district']['id'];
        $model->district = $district_data['data']['district']['district']['name'];

        $model->addtime = time();
        $model->is_delete = 0;
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $model->attributes,
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
