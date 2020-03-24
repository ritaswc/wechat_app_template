<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/6/28 14:56
 */


namespace app\controllers;

use app\models\tplmsg\BindWechatPlatform;
use app\models\Option;
use app\models\Store;
use app\models\User;
use Curl\Curl;
use luweiss\wechat\Wechat;
use yii\helpers\VarDumper;

class WechatPlatformController extends Controller
{
    /** @var Store $store */
    public $store;

    /** @var Wechat $wechat */
    public $wechat;

    public $config;

    public function init()
    {
        $this->store = Store::findOne([
            'id' => \Yii::$app->request->get('store_id'),
        ]);
        if (!$this->store) {
            throw new \Exception('错误的Store Id。');
        }
        $this->setWechat();
        parent::init();
    }

    public function actionBindUser($code = null)
    {
        $this->layout = false;
        if (!$code) {
            return $this->oauth();
        }
        try {
            list($access_token, $openid) = $this->getAccessTokenAndOpenId($code);
            $res = $this->getUserInfo($access_token, $openid);
            if (!$res['unionid']) {
                throw new \Exception('当前公众号未绑定微信开放平台。');
            }
            $this->bindUser($res['unionid'], $res['openid']);
            return $this->render('bind-user', [
                'code' => 0,
                'app_name' => $this->config['app_name'],
                'app_qrcode' => $this->config['app_qrcode'],
            ]);
        } catch (\Exception $e) {
            return $this->render('bind-user', [
                'code' => 1,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    private function setWechat()
    {
        $form = new BindWechatPlatform();
        $form->store_id = $this->store->id;
        $data = $form->search();
        $this->wechat = new Wechat([
            'appId' => $data['app_id'],
            'appSecret' => $data['app_secret'],
        ]);
        $this->config = $data;
    }

    private function oauth()
    {
        $redirect_uri = \Yii::$app->urlManager->createAbsoluteUrl(['wechat-platform/bind-user', 'store_id' => $this->store->id]);
        $redirect_uri = str_replace('http://', 'https://', $redirect_uri);
        $redirect_uri = urlencode($redirect_uri);
        $scope = 'snsapi_userinfo';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->wechat->appId}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
        return \Yii::$app->response->redirect($url);
    }

    /**
     * @return \ArrayObject|mixed ['access_token','openid']
     * @throws \Exception
     */
    private function getAccessTokenAndOpenId($code)
    {
        $api = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->wechat->appId}&secret={$this->wechat->appSecret}&code={$code}&grant_type=authorization_code";
        $res = \Yii::$app->serializer->decode($this->curlGet($api));
        if (!$res['access_token']) {
            if ($res['errcode'] == 40163) {
                $this->oauth();
            } else {
                throw new \Exception($res['errmsg']);
            }
        }
        return [$res['access_token'], $res['openid']];
    }

    /**
     * @param $access_token
     * @param $open_id
     * @return \ArrayObject|mixed
     * @throws \ErrorException
     */
    private function getUserInfo($access_token, $open_id)
    {
        $api = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
        $res = \Yii::$app->serializer->decode($this->curlGet($api));
        if (!$res['openid']) {
            throw new \Exception($res['errmsg']);
        }
        return $res;
    }

    private function bindUser($unionid, $openid)
    {
        $user = User::findOne([
            'wechat_union_id' => $unionid,
            'store_id' => $this->store->id,
        ]);
        if (!$user) {
            throw new \Exception('小程序未绑定开放平台或您未在小程序上登录。unionid=' . $unionid);
        }
        $user->wechat_platform_open_id = $openid;
        $user->save();
        return true;
    }

    private function getQrcode()
    {
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
        $res = \Yii::$app->serializer->decode($this->curlGet($api));
        if (!$res['openid']) {
            throw new \Exception($res['errmsg']);
        }
        return $res;
    }

    /**
     * @return Curl
     * @throws \ErrorException
     */
    private function getCurl()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $curl;
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    private function curlGet($url, $data = null)
    {
        $curl = $this->getCurl();
        $curl->get($url, $data);
        if ($curl->error_code) {
            throw new \Exception($curl->error_message);
        }
        return $curl->response;
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    private function curlPost($url, $data = null)
    {
        $curl = $this->getCurl();
        $curl->post($url, $data);
        if ($curl->error_code) {
            throw new \Exception($curl->error_message);
        }
        return $curl->response;
    }
}
