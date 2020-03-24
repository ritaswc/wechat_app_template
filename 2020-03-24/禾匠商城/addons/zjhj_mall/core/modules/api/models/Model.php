<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/5
 * Time: 16:01
 */

namespace app\modules\api\models;

use luweiss\wechat\Wechat;

class Model extends \app\models\Model
{
    /**
     * @return Wechat
     */
    public function getWechat()
    {
        return isset(\Yii::$app->controller->wechat) ? \Yii::$app->controller->wechat : null;
    }
}
