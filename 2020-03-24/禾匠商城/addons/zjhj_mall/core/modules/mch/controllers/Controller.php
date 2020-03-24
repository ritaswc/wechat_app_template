<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/26
 * Time: 14:13
 */

namespace app\modules\mch\controllers;

use app\hejiang\Cloud;
use app\models\AdminPermission;
use app\models\Option;
use app\models\Store;
use app\models\StorePermission;
use app\models\We7UserAuth;
use app\models\WechatApp;
use app\modules\mch\behaviors\PermissionBehavior;
use app\modules\mch\models\MchMenu;
use luweiss\wechat\Wechat;
use Yii;

/**
 * @property Wechat $wechat
 */
class Controller extends \app\controllers\Controller
{
    public $layout = 'main';
    public $store;
    /* @var Wechat $wechat */
    public $wechat;
    public $wechat_app;

    /** @var bool $is_admin 是否是总管理员账号登录 */
    public $is_admin = false;
    /** @var bool $is_we7 是否是微擎环境 */
    public $is_we7 = false;
    /** @var bool $is_ind 是否是独立版 */
    public $is_ind = false;
    /** @var bool $is_we7_offline 是否是微擎线下版（本地开发） */
    public $is_we7_offline = false;
    public $platform = null;
    public $we7_user_auth = null;
    public $ind_user_auth = null;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'permission' => [
                'class' => PermissionBehavior::className(),
            ],
        ]);
    }

    public function init()
    {
        $this->setAdminLoginUrl();
        parent::init();
        $this->store = Store::findOne([
            'id' => \Yii::$app->session->get('store_id'),
        ]);

        if (Yii::$app->admin->isGuest == false) {
            $this->is_we7 = false;
            $this->is_ind = true;
            $this->platform = 'ind';
            if (\Yii::$app->admin->id != $this->store->admin_id && \Yii::$app->admin->id != 1) {
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin/default/index']))->send();
                \Yii::$app->end();
            }
        }

        if (empty($this->store)) {
            $url = $_COOKIE['adminLoginUrl'];
            \Yii::$app->response->redirect($url)->send();
            \Yii::$app->end();
        }
        Yii::$app->store = $this->store;
        $this->wechat_app = WechatApp::findOne(['id' => $this->store->wechat_app_id]);

        if (!is_dir(\Yii::$app->runtimePath . '/pem')) {
            mkdir(\Yii::$app->runtimePath . '/pem');
            file_put_contents(\Yii::$app->runtimePath . '/pem/index.html', '');
        }
        $cert_pem_file = null;
        if ($this->wechat_app->cert_pem) {
            $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_app->cert_pem);
            if (!file_exists($cert_pem_file)) {
                file_put_contents($cert_pem_file, $this->wechat_app->cert_pem);
            }
        }
        $key_pem_file = null;
        if ($this->wechat_app->key_pem) {
            $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_app->key_pem);
            if (!file_exists($key_pem_file)) {
                file_put_contents($key_pem_file, $this->wechat_app->key_pem);
            }
        }
        $this->wechat = new Wechat([
            'appId' => $this->wechat_app->app_id,
            'appSecret' => $this->wechat_app->app_secret,
            'mchId' => $this->wechat_app->mch_id,
            'apiKey' => $this->wechat_app->key,
            'certPem' => $cert_pem_file,
            'keyPem' => $key_pem_file,
        ]);

        if (!\Yii::$app->admin->isGuest) {
            if (\Yii::$app->admin->id == 1) {
                $this->is_admin = true;
            }
        } elseif (\Yii::$app->mchRoleAdmin->identity->type == 2) {
            $this->is_admin = false;
        } else {
            if (isset($_SESSION['we7_user']['uid']) && $_SESSION['we7_user']['uid'] == 1) {
                $this->is_admin = true;
            }

            $this->is_we7 = true;
            $this->is_ind = false;
            $this->platform = 'we7';
            $we7_user_auth_model = We7UserAuth::findOne(['we7_user_id' => \Yii::$app->user->identity->we7_uid]);
            $all_permission = $this->getAllPermission();
            if (!$we7_user_auth_model || $we7_user_auth_model->auth == null) {
                //账户没设置过权限，管理员默认有所有权限，子账户默认无权限
                if ($this->is_admin) {
                    $this->we7_user_auth = $all_permission;
                } else {
                    $we7_default_all_permission = Option::get('we7_default_all_permission');
                    $this->we7_user_auth = $we7_default_all_permission ? $all_permission : [];
                }
            } else {
                //已设置过权限的使用已设置的权限
                $this->we7_user_auth = json_decode($we7_user_auth_model->auth, true);
            }
            if ($this->is_admin) {
                $this->we7_user_auth = $all_permission;
            }
            if (stripos(\Yii::$app->request->baseUrl, 'zjhj_mall_offline') !== false) {
                $this->is_we7_offline = true;
            }
        }

        if (\Yii::$app->request->hostName == 'localhost') {
            $this->is_we7_offline = true;
        }

        if (file_exists(\Yii::$app->basePath . '/we7_offline')) {
            $this->is_we7_offline = true;
        }
        // session记录当前是否是管理员
        Yii::$app->session->set('__is_admin', $this->is_admin);
    }

    /**
     * 检查是否是总管理员，不是管理员则转到首页或指定页面
     * @param String $return_url 跳转的页面
     * @return boolean
     */
    public function checkIsAdmin($return_url = null)
    {
        if (!$this->is_admin) {
            $return_url = $return_url ? $return_url : \Yii::$app->urlManager->createUrl(['mch/store/index']);
            $this->redirect($return_url)->send();
            \Yii::$app->end();
        }
        return true;
    }

    public function getAllPermission()
    {
        $list = AdminPermission::getList();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = $item->name;
        }

        return $new_list;
    }

    public function getMenuList()
    {
        $cacheKey = $this->getMenuCacheKey();
        if ($res = Yii::$app->getCache()->get($cacheKey)) {
            return $res;
        }

        $m = new MchMenu();
        $m->platform = $this->platform;
        if ($this->is_we7) {
            $m->user_auth = $this->we7_user_auth;
            $this->is_we7_offline ? $m->offline = true : $m->offline = false;

        } else {
            $m->offline = true;
        }

        if ($this->is_ind) {
            $m->user_auth = json_decode(\Yii::$app->admin->identity->permission, true);
        }

        $m->is_admin = $this->is_admin;
        $res = $m->getList();
        Yii::$app->getCache()->set($cacheKey, $res, 3600);

        return $res;
    }

    /**
     * 现只用于左侧菜单缓存
     * @return string
     */
    public function getMenuCacheKey()
    {
        //根据商城ID和用户accessToken 作为用户菜单的唯一标识符
        $storeId = $this->store->id;
        if (!Yii::$app->mchRoleAdmin->isGuest) {
            $accessToken = Yii::$app->mchRoleAdmin->identity->access_token;
        }

        if (!Yii::$app->user->isGuest) {
            $accessToken = Yii::$app->user->identity->access_token;
        }

        if (!Yii::$app->admin->isGuest) {
            $accessToken = Yii::$app->admin->identity->access_token;
        }

        $cacheKey = 'mch-' . $storeId . $accessToken;

        return $cacheKey;
    }

    /**
     * session失效后，根据cookie存储的路由跳转相应的登录页面
     */
    public function setAdminLoginUrl()
    {
        if (!Yii::$app->mchRoleAdmin->isGuest) {
            $urlManager = Yii::$app->urlManager;
            $url = $urlManager->hostInfo . $urlManager->baseUrl . '/role.php?store_id=' . Yii::$app->mchRoleAdmin->identity->store_id;
            setcookie('adminLoginUrl', $url, time() + 24 * 3600);
        }

        if (!Yii::$app->user->isGuest) {
            $current_url = \Yii::$app->request->absoluteUrl;
            $key = 'addons/';
            $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
            $url = $we7_url . "web/index.php?c=account&a=display&type=all";
            setcookie('adminLoginUrl', $url, time() + 24 * 3600);
        }

        if (!Yii::$app->admin->isGuest) {
            $url = \Yii::$app->urlManager->createUrl(['admin/default/index']);
            setcookie('adminLoginUrl', $url, time() + 24 * 3600);
        }
    }


    /**
     * 获取当前用户拥有的插件权限
     * 微擎版|独立版|操作员
     * @return mixed|null
     */
    public function getUserAuth()
    {
        if (isset(Yii::$app->mchRoleAdmin)) {
            $userAuth = StorePermission::getOpenPermissionList($this->store);
        }
        if ($this->is_we7) {
            $userAuth = $this->we7_user_auth;
        }
        if ($this->is_ind) {
            $userAuth = json_decode(\Yii::$app->admin->identity->permission, true);
        }

        return $userAuth;
    }
}
