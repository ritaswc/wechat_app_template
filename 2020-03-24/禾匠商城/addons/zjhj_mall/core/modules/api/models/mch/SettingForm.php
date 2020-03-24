<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/6
 * Time: 15:19
 */

namespace app\modules\api\models\mch;


use app\models\DistrictArr;
use app\models\Mch;
use app\models\MchCommonCat;
use app\modules\api\models\ApiModel;

class SettingForm extends ApiModel
{
    /** @var  Mch $mch */
    public $mch;

    public function search()
    {
        $mch_common_cat_list = MchCommonCat::find()->where(['store_id' => $this->mch->store_id, 'is_delete' => 0])
            ->select('id,name')->orderBy('sort')->asArray()->all();
        $mch_common_cat_name = '';
        foreach ($mch_common_cat_list as $item) {
            if ($item['id'] == $this->mch->mch_common_cat_id) {
                $mch_common_cat_name = $item['name'];
                break;
            }
        }
        $mch_data = $this->mch->getAttributes(['realname', 'tel','wechat_name', 'name', 'province_id', 'city_id', 'district_id', 'address', 'mch_common_cat_id', 'service_tel', 'logo', 'header_bg',]);
        $mch_data['mch_common_cat_name'] = $mch_common_cat_name;
        $province = DistrictArr::getDistrict($this->mch->province_id);
        $city = DistrictArr::getDistrict($this->mch->city_id);
        $district = DistrictArr::getDistrict($this->mch->district_id);
        return [
            'code' => 0,
            'data' => [
                'mch' => $mch_data,
                'edit_district' => [
                    'province' => [
                        'id' => $this->mch->province_id,
                        'name' => $province->name,
                    ],
                    'city' => [
                        'id' => $this->mch->city_id,
                        'name' => $city->name,
                    ],
                    'district' => [
                        'id' => $this->mch->district_id,
                        'name' => $district->name,
                    ],
                ],
                'mch_common_cat_list' => $mch_common_cat_list,
            ],
        ];
    }
}