<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 15:14
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\hejiang\exceptions\InvalidResponseException;
use app\models\Store;
use app\models\StorePermission;
use app\models\WechatApp;
use app\models\WechatPlatform;
use app\modules\api\behaviors\StoreStatusBehavior;
use luweiss\wechat\Wechat;
use yii\web\Response;

/**
 * @property Store $store
 * @property WechatApp $wechat_app
 * @property Wechat $wechat 小程序的
 * @property Wechat $wechat_of_platform 公众号的
 */
class Controller extends \app\controllers\Controller
{
    public $store_id;
    public $store;
    public $wechat_app;
    public $wechat_platform;
    public $wechat;
    public $wechat_of_platform;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'storeStatus' => [
                'class' => StoreStatusBehavior::className(),
            ],
        ]);
    }

    public function init()
    {
        $this->enableCsrfValidation = false;
        try {
            $this->initStore();
        } catch (\Exception $e) {
            return new ApiResponse(1, $e->getMessage());
        }

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
            'cachePath' => \Yii::$app->runtimePath . '/cache',
            'certPem' => $cert_pem_file,
            'keyPem' => $key_pem_file,
        ]);

        $this->setWechatOfPlatform();

        $access_token = \Yii::$app->request->get('access_token');
        if (!$access_token) {
            $access_token = \Yii::$app->request->post('access_token');
        }
        if ($access_token) {
            \Yii::$app->user->loginByAccessToken($access_token);
        }

        $response = &\Yii::$app->response;
        if (YII_DEBUG) {
            $response->format = Response::FORMAT_HTML; // Debug 模式若报错展示 HTML
        } else {
            $response->format = Response::FORMAT_JSON;
        }
        $response->on(Response::EVENT_BEFORE_SEND, [$this, 'beforeSend']);
    }

    private function initStore()
    {
        if (WE7_BRANCH == 'multiple') {
            $_acid = \Yii::$app->request->get('_acid');
            if (!$_acid) {
                $_acid = \Yii::$app->request->post('_acid');
            }
            $this->store_id = \Yii::$app->request->get('store_id');
            if ($_acid && $_acid != -1) {
                $this->store = Store::findOne([
                    'acid' => $_acid,
                ]);
            } else {
                $this->store = Store::findOne($this->store_id);
            }
            if (!$this->store) {
                throw new \Exception('Store Is Null');
            }
            \Yii::$app->store = $this->store;
            $this->store_id = $this->store->id;
            $this->wechat_app = WechatApp::findOne($this->store->wechat_app_id);
            if (!$this->wechat_app) {
                throw new \Exception('Wechat App Is Null');
            }
        } else {
            $this->wechat_app = WechatApp::find()->limit(1)->one();
            if (!$this->wechat_app) {
                throw new \Exception('Wechat App Is Null');
            }
            $this->store = Store::findOne(['wechat_app_id' => $this->wechat_app->id]);
            if (!$this->store) {
                throw new \Exception('Store Is Null');
            }
            \Yii::$app->store = $this->store;
            $this->store_id = $this->store->id;
        }
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeSend($event)
    {
        /* @var $response \yii\web\Response */
        $response = $event->sender;
        /* @var $data \app\hejiang\ApiResponse|\app\hejiang\BaseApiResponse */
        $data = &$response->data;

        if ($response->isSuccessful) {
            if (!($data instanceof BaseApiResponse)) {
                throw new InvalidResponseException('API response must be a instance which extends BaseApiResponse, but given ' . get_class($data));
            }
            $response->format = Response::FORMAT_JSON;
        }

        if ($data instanceof BaseApiResponse) {
            $data = $data->raw;
        }
    }

    private function setWechatOfPlatform()
    {
        if ($this->store->use_wechat_platform_pay == 1) {
            $this->wechat_platform = WechatPlatform::findOne($this->store->wechat_platform_id);
            if (!$this->wechat_platform) {
                return new ApiResponse(1, 'Wechat Platform Is Null');
            }

            $cert_pem_file = null;
            if ($this->wechat_platform->cert_pem) {
                $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_platform->cert_pem);
                if (!file_exists($cert_pem_file)) {
                    file_put_contents($cert_pem_file, $this->wechat_platform->cert_pem);
                }
            }

            $key_pem_file = null;
            if ($this->wechat_platform->key_pem) {
                $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_platform->key_pem);
                if (!file_exists($key_pem_file)) {
                    file_put_contents($key_pem_file, $this->wechat_platform->key_pem);
                }
            }

            $this->wechat_of_platform = new Wechat([
                'appId' => $this->wechat_platform->app_id,
                'appSecret' => $this->wechat_platform->app_secret,
                'mchId' => $this->wechat_platform->mch_id,
                'apiKey' => $this->wechat_platform->key,
                'cachePath' => \Yii::$app->runtimePath . '/cache',
                'certPem' => $cert_pem_file,
                'keyPem' => $key_pem_file,
            ]);
        }
    }

    /**
     * 获取当前商城的插件权限
     */
    public function getStorePermissions()
    {
        $storeAuth = StorePermission::getOpenPermissionList($this->store);

        return $storeAuth;
    }
}
