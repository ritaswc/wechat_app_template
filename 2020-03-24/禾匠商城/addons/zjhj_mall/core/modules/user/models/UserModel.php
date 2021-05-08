<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/12
 * Time: 11:16
 */

namespace app\modules\user\models;

use app\models\Model;
use luweiss\wechat\Wechat;

class UserModel extends Model
{
    /**
     * @return Wechat
     */
    public function getWechat()
    {
        return empty(\Yii::$app->controller->wechat) ? null : \Yii::$app->controller->wechat;
    }
}
