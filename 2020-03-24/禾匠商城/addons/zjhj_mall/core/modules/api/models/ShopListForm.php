<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/24
 * Time: 10:37
 */

namespace app\modules\api\models;

use app\hejiang\ApiResponse;
use app\models\Shop;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ShopListForm extends ApiModel
{
    public $store_id;
    public $user;

    public $longitude;
    public $latitude;
    public $page;
    public $limit;
    public $keyword;

    public function rules()
    {
        return [
            [['longitude', 'latitude','keyword'], 'trim'],
            [['page'], 'integer'],
            [['page'], 'default', 'value' => 0],
            [['limit'], 'integer'],
            [['limit'], 'default', 'value' => 20],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Shop::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0
        ]);
        if ($this->keyword) {
            $query->andWhere(['like','name',$this->keyword]);
        }

        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page]);

        $list = $query->all();
        $list = ArrayHelper::toArray($list);
        $distance = array();
        foreach ($list as $index => $item) {
            $list[$index]['distance'] = -1;
            if ($item['longitude'] && $this->longitude) {
                $from = [$this->longitude, $this->latitude];
                $to = [$item['longitude'], $item['latitude']];
                $list[$index]['distance'] = $this->get_distance($from, $to, false, 2);
            }
            $distance[] = $list[$index]['distance'];
        }
        array_multisort($distance, SORT_ASC, $list);
        $min = min(count($list), $this->limit);
        $offset = $this->page * $this->limit;
        $list_arr = array();
        $data_count = 1;
        foreach ($list as $index => $item) {
            $list[$index]['score'] = (int)$item['score'];
            if ($index < $offset) {
                continue;
            }
            if ($data_count <= $this->limit) {
                $list[$index]['distance'] = $this->distance($item['distance']);
                array_push($list_arr, $list[$index]);
                $data_count++;
            } else {
                break;
            }
        }
        $data = [
            'list' => $list_arr,
            'page_count' => $p->pageCount,
            'row_count' => $count
        ];
        return new ApiResponse(0, 'success', $data);
    }


    private static function distance($distance)
    {
        if ($distance == -1) {
            return -1;
        }
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
