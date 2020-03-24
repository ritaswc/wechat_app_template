<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 11:15
 */

namespace app\modules\api\models;

use app\models\Option;
use app\models\Shop;
use Curl\Curl;
use yii\helpers\VarDumper;

class LocationForm extends ApiModel
{
    public $store_id;

    public $longitude;
    public $latitude;

    public function rules()
    {
        return [
            [['longitude', 'latitude'], 'trim']
        ];
    }

    public function search()
    {

        $list = Shop::find()->select(['address', 'mobile', 'id', 'name', 'longitude', 'latitude'])
            ->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();

//        $from = $this->latitude.','.$this->longitude;
//        $key = Option::get('tencent_api_key',0,'admin');
//        foreach($list as $index=>$item){
//            $list[$index]['distance'] = 0;
//            if($item['longitude'] && $this->longitude){
//                $to = $item['latitude'].','.$item['longitude'];
//                $api = 'http://apis.map.qq.com/ws/distance/v1/?parameters&from='.$from.'&to='.$to.'&key='.$key;
//                $data = array();
//                $curl = new Curl();
//                $curl->get($api,$data);
//                $res = json_decode($curl->response);
//                VarDumper::dump($curl,3,1);
//                if($res->status == 0){
//                    $result = $res->result->elements;
//                    $list[$index]['distance'] = $this->distance($result[0]->distance);
//                }
//                //var_dump($result);
//            }
//        }

        foreach ($list as $index => $item) {
            $list[$index]['distance'] = 0;
            if ($item['longitude'] && $this->longitude) {
                $from = [$this->longitude, $this->latitude];
                $to = [$item['longitude'], $item['latitude']];
                $list[$index]['distance'] = $this->distance($this->get_distance($from, $to, false, 2));
            }
        }
        array_multisort(array_column($list, 'distance'), SORT_ASC, $list);
        $min = min(count($list), 30);
        $list_arr = array();
        foreach ($list as $index => $item) {
            if ($index <= $min) {
                array_push($list_arr, $item);
            }
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => $list_arr
        ];
    }

    private static function distance($distance)
    {
        if ($distance > 1000) {
            $distance = round($distance / 1000, 2) . 'km';
        } else {
            $distance .= 'm';
        }
        return $distance;
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from  [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    function get_distance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}
