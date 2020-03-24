<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\api\behaviors;

use app\hejiang\ApiCode;
use app\hejiang\ApiResponse;
use app\models\Store;
use yii\base\ActionFilter;
use Yii;

class StoreStatusBehavior extends ActionFilter
{
    private $safe = [
        'api/default/store',
        'api/default/cat-list',
        'api/default/navigation-bar-color'
    ];

    public function beforeAction($e)
    {

        $route = Yii::$app->controller->route;
        if (in_array($route, $this->safe)) {
            return true;
        }

        $storeId = Yii::$app->controller->store_id;
        $store = Store::findOne($storeId);

        if ($store->status) {
            Yii::$app->response->data = new ApiResponse(ApiCode::CODE_STORE_DISABLED, '小程序已被禁用');
            return false;
        }

        return true;
    }
}
