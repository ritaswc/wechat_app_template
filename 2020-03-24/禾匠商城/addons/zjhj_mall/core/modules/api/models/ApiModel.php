<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/5
 * Time: 16:01
 */

namespace app\modules\api\models;

use app\models\Model;
use luweiss\wechat\Wechat;
use app\models\alipay\MpConfig;

class ApiModel extends Model
{
    /**
     * @return Wechat
     */
    public static function getWechat()
    {
        return isset(\Yii::$app->controller->wechat) ? \Yii::$app->controller->wechat : null;
    }

    /**
     * 获取 Alipay 客户端
     *
     * @return \Alipay\AopClient
     */
    public static function getAlipay($storeId = null)
    {
        $config = MpConfig::get($storeId ?: \Yii::$app->controller->store->id);
        return $config->getClient();
    }
}
