<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/27
 * Time: 1:05
 */

namespace app\modules\api\behaviors;

use app\hejiang\ApiResponse;
use yii\base\ActionFilter;
use yii\web\Controller;

class LoginBehavior extends ActionFilter
{
    public $ignore;

    public function beforeAction($e)
    {
        if (is_array($this->ignore) && in_array($e->id, $this->ignore)) {
            return true;
        }
        $access_token = \Yii::$app->request->get('access_token');
        if (!$access_token) {
            $access_token = \Yii::$app->request->post('access_token');
        }
        if (!$access_token) {
            \Yii::$app->response->data = new ApiResponse(-1, 'access_token 不能为空');
            return false;
        }
        if (\Yii::$app->user->loginByAccessToken($access_token)) {
            return true;
        } else {
            \Yii::$app->response->data = new ApiResponse(-1, '登录失败,');
            return false;
        }
    }
}
