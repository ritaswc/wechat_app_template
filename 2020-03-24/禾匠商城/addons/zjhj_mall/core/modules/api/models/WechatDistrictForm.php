<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/8
 * Time: 11:15
 */

namespace app\modules\api\models;

use app\models\DistrictArr;

class WechatDistrictForm extends ApiModel
{
    public $national_code;

    public $province_name;
    public $city_name;
    public $county_name;

    public function rules()
    {
        return [
            [['national_code', 'province_name', 'city_name', 'county_name',], 'safe',],
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->errorResponse;
        return DistrictArr::getWechatDistrict($this->province_name, $this->city_name, $this->county_name);
    }

}
