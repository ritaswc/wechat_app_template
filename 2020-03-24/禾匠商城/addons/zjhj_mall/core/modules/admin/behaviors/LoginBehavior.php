<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 14:13
 */

namespace app\modules\admin\behaviors;

use app\models\Admin;
use yii\base\ActionFilter;

class LoginBehavior extends ActionFilter
{

    /**
     * @param \yii\base\Action $e
     * @return bool
     */
    public function beforeAction($e)
    {

        if (!\Yii::$app->admin->isGuest) {
            return $this->checkExpire();
        }
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->data = [
                'code' => -1,
                'msg' => '请先登录'
            ];
        } else {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('admin/passport/login'))->send();
        }
        return false;
    }

    private function checkExpire()
    {
        /** @var Admin $admin */
        $admin = \Yii::$app->admin->identity;
        if ($admin->expire_time == 0 || time() < $admin->expire_time) {
            return true;
        }
        echo \Yii::$app->view->render('/account-expire');
        \Yii::$app->end();
        return false;
    }
}
