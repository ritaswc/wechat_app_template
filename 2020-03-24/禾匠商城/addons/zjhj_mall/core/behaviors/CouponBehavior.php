<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/25
 * Time: 17:01
 */

namespace app\behaviors;

use app\models\UserCoupon;
use yii\web\Controller;

/**
 * 处理过期优惠券
 */
class CouponBehavior extends BaseBehavior
{
    protected $only_routes = [
        'mch/coupon/*',
        'api/order/submit-preview',
        'api/order/submit',
        'api/order/submit',
        'api/coupon/*',
        'mch/user/coupon'
    ];

    /**
     * @param \yii\base\ActionEvent $e
     */
    public function beforeAction($e)
    {
        \Yii::warning('----COUPON BEHAVIOR----');
        if (empty($e->action->controller->store)) {
            return;
        }

        $store_id = $e->action->controller->store->id;
        /* 使用下面的，处理死锁
        UserCoupon::updateAll(['is_expire' => 1], [
        'AND',
        ['store_id' => $store_id,],
        ['is_use' => 0,],
        ['is_expire' => 0,],
        ['is_delete' => 0,],
        ['<', 'end_time', time()],
        ]);
         */
        $idList =
        UserCoupon::find()->select('id')
            ->where([
                'AND',
                ['store_id' => $store_id],
                ['is_use' => 0],
                ['is_expire' => 0],
                ['is_delete' => 0],
                ['<', 'end_time', time()],
            ])->asArray()->all();
        if (empty($idList)) {
            return;
        }

        $newIdList = [];
        foreach ($idList as $item) {
            $newIdList[] = $item['id'];
        }

        unset($item);
        UserCoupon::updateAll(['is_expire' => 1], [
            'id' => $newIdList,
        ]);
    }
}
