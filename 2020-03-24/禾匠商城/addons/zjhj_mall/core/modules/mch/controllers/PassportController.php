<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:40
 */

namespace app\modules\mch\controllers;

use app\models\common\admin\log\CommonActionLog;
use app\models\Store;
use app\models\User;
use app\models\WechatApp;
use yii\helpers\VarDumper;
use Yii;

class PassportController extends \app\controllers\Controller
{
    public function actionLogin()
    {
        $we7_user = \Yii::$app->session->get('we7_user');
        $we7_account = \Yii::$app->session->get('we7_account');

        if (empty($we7_user) || empty($we7_account)) {
            $current_url = \Yii::$app->request->absoluteUrl;
            $key = 'addons/' . WE7_MODULE_NAME . '/core/web/';
            $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
            $this->redirect($we7_url)->send();
            exit;
        }

        $user = User::findOne([
            'we7_uid' => $we7_user['uid'],
        ]);
        if (!$user) {
            $user = User::findOne([
                'username' => $we7_user['username'],
            ]);
        }
        if (!$user) {
            $user = new User();
            $user->username = $we7_user['username'];
            $user->type = 0;
            $user->password = \Yii::$app->security->generatePasswordHash(md5(uniqid()));
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->addtime = time();
            $user->nickname = $we7_user['username'];
            $user->avatar_url = '0';
            $user->store_id = 0;
            $user->we7_uid = $we7_user['uid'];
            $user->save();
            if (!$user->save()) {
                VarDumper::dump($user->errors);
                exit;
            }
        } elseif (!$user->we7_uid || $user->we7_uid != $we7_user['uid']) {
            $user->we7_uid = $we7_user['uid'];
            $user->save();
        }

        Yii::$app->user->login($user);

        if (WE7_BRANCH == 'multiple') {
            $wechat_app = WechatApp::findOne([
                'acid' => $we7_account['acid'],
            ]);
        } else {
            $wechat_app = WechatApp::find()->limit(1)->one();
        }
        if (!$wechat_app) {
            $wechat_app = new WechatApp();
            $wechat_app->acid = $we7_account['acid'];
            $wechat_app->user_id = $user->id;
            $wechat_app->name = $we7_account['name'];
            $wechat_app->app_id = '0';
            $wechat_app->app_secret = '0';
            if (!$wechat_app->save()) {
                VarDumper::dump($wechat_app->errors);
                exit;
            }
        }

        $store = Store::findOne([
            'wechat_app_id' => $wechat_app->id,
        ]);
        if (!$store) {
            $store = new Store();
            $store->acid = $wechat_app->acid;
            $store->user_id = $user->id;
            $store->wechat_app_id = $wechat_app->id;
            $store->name = $we7_account['name'];
            if (!$store->save()) {
                VarDumper::dump($store->errors);
                exit;
            }
        }

        \Yii::$app->session->set('store_id', $store->id);

        CommonActionLog::storeActionLog('', 'login', 0, [], $user->id);

        $this->redirect(\Yii::$app->urlManager->createUrl(['mch/store/index']))->send();
    }

    public function actionLogout()
    {
        $url = $_COOKIE['adminLoginUrl'];
        \Yii::$app->session->remove('store_id');
        \Yii::$app->response->redirect($url)->send();
        \Yii::$app->end();
    }
}
