<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/31
 * Time: 17:16
 */

namespace app\modules\mch\models\integralmall;

use app\modules\mch\models\MchModel;
use app\models\Coupon;
use yii\data\Pagination;

class CouponForm extends MchModel
{
    public function getList($store_id, $keyword)
    {
        $query = Coupon::find()
            ->where(['store_id' => $store_id, 'is_delete' => 0,'is_integral'=>2]);
        if ($keyword) {
            $keyword = trim($keyword);
            $query->andWhere(['LIKE', 'name', $keyword]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 10]);
        $list = $query
            ->offset($p->offset)
            ->limit($p->limit)
            ->all();
        return [$list, $p];
    }
    public function getList2($store_id, $keyword)
    {
        $query = Coupon::find()
            ->where(['store_id' => $store_id, 'is_delete' => 0]);
        if ($keyword) {
            $keyword = trim($keyword);
            $query->andWhere(['LIKE', 'name', $keyword]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 10]);
        $list = $query
            ->offset($p->offset)
            ->limit($p->limit)
            ->all();
        return [$list, $p];
    }
}
