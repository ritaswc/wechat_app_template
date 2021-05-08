<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/25
 * Time: 15:44
 */

namespace app\modules\api\models;

use app\hejiang\ApiCode;
use app\models\Address;
use app\models\DistrictArr;
use app\models\Model;
use app\models\Option;

class AddressSaveForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $address_id;
    public $name;
    public $mobile;
    public $province_id;
    public $city_id;
    public $district_id;
    public $detail;

    public function rules()
    {
        return [
            [['name', 'mobile', 'province_id', 'city_id', 'district_id', 'detail',], 'trim'],
            [['name', 'mobile', 'province_id', 'city_id', 'district_id', 'detail',], 'required'],
            [['address_id',], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'        => '收货人',
            'mobile'      => '联系电话',
            'province_id' => '所在地区',
            'city_id'     => '所在地区',
            'district_id' => '所在地区',
            'detail'      => '详细地址',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $option = Option::getList('mobile_verify', \Yii::$app->controller->store->id, 'admin', 1);
        if ($option['mobile_verify']) {
            if (!preg_match(Model::MOBILE_VERIFY, $this->mobile)) {
                return [
                    'code' => 1,
                    'msg' => '请输入正确的手机号'
                ];
            }
        }

        $address = Address::findOne([
            'id' => $this->address_id,
            'is_delete' => 0,
            'user_id' => $this->user_id
        ]);

        if (!$address) {
            $address = new Address();
            $address->store_id = $this->store_id;
            $address->user_id = $this->user_id;
            $address->is_delete = Address::DELETE_STATUS_FALSE;
            $address->is_default = Address::DEFAULT_STATUS_FALSE;
            $address->addtime = time();
        };
        $address->name = trim($this->name);
        $address->mobile = $this->mobile;
        $address->detail = trim($this->detail);

        $province = DistrictArr::getDistrict($this->province_id);
        if (!$province) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg'  => '省份数据错误，请重新选择',
            ];
        }
        $address->province_id = $province->id;
        $address->province = $province->name;

        $city = DistrictArr::getDistrict($this->city_id);
        if (!$city) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg'  => '城市数据错误，请重新选择',
            ];
        }
        $address->city_id = $city->id;
        $address->city = $city->name;

        $district = DistrictArr::getDistrict($this->district_id);
        if (!$district) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg'  => '地区数据错误，请重新选择',
            ];
        }
        $address->district_id = $district->id;
        $address->district = $district->name;

        if ($address->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg'  => '保存成功',
            ];
        } else {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg'  => '操作失败，请稍后重试',
            ];
        }
    }
}
