<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/28 11:30
 */


namespace app\hejiang\cloud;


use app\models\Form;
use app\models\Model;
use app\models\Plugin;

class CloudPlugin
{
    const REQUEST_METHOD_GET = 'REQUEST_METHOD_GET';
    const REQUEST_METHOD_POST = 'REQUEST_METHOD_POST';
    const INSTALLED_LIST_CACHE_KET = 'INSTALLED_LIST_CACHE_KET';
    const DECODE_KEY = 'odhjqowdja8u298yhqd9qwydasyioh230912doj238';

    private static $installedPluginList = null;

    /**
     * @return mixed
     * @throws CloudException
     * @throws \yii\db\StaleObjectException
     */
    public static function getList()
    {
        $res = static::sendRequest(static::REQUEST_METHOD_GET, CloudApi::PLUGIN_LIST);
        if ($res['code'] !== 0) {
            return $res;
        }
        foreach ($res['data']['list'] as &$item) {
            $item['installed'] = static::isPluginInstalled($item['name']);
        }
        return $res;
    }

    /**
     * @param $id
     * @return mixed
     * @throws CloudException
     * @throws \yii\db\StaleObjectException
     */
    public static function getDetail($id)
    {
        $res = static::sendRequest(static::REQUEST_METHOD_GET, CloudApi::PLUGIN_DETAIL, [
            'id' => $id,
        ]);
        if ($res['code'] !== 0) {
            return $res;
        }
        $res['data']['installed'] = static::isPluginInstalled($res['data']['name']);
        return $res;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function createOrder($id)
    {
        return static::sendRequest(static::REQUEST_METHOD_POST, CloudApi::PLUGIN_CREATE_ORDER, [], [
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\Exception
     * @throws CloudException
     */
    public static function install($id)
    {
        $res = static::sendRequest(static::REQUEST_METHOD_GET, CloudApi::PLUGIN_INSTALL, [
            'id' => $id,
        ]);
        if ($res['code'] !== 0) {
            throw new \Exception('安装出错：' . $res['msg']);
        }
        sleep(rand(1, 3));
        if (empty($res['data']['info']['name'])
            || empty($res['data']['info']['display_name'])
            || empty($res['data']['info']['route'])
        ) {
            throw new \Exception('安装出错：插件信息错误。');
        }
        $exists = Plugin::find()->where(['name' => $res['data']['info']['name']])->exists();
        if ($exists) {
            \Yii::$app->cache->delete(static::INSTALLED_LIST_CACHE_KET);
            throw new \Exception('您已安装过该插件，请刷新页面查看。');
        }
        $model = new Plugin();
        $model->data = \Yii::$app->security->generateRandomString(32);
        $model->name = $res['data']['info']['name'];
        $model->display_name = $res['data']['info']['display_name'];
        $model->route = $res['data']['info']['route'];
        $model->addtime = time();
        if ($model->save()) {
            \Yii::$app->cache->delete(static::INSTALLED_LIST_CACHE_KET);
            return [
                'code' => 0,
                'msg' => '安装成功。',
            ];
        } else {
            throw new \Exception('插件安装失败：' . (new Model())->getErrorResponse($model)->msg);
        }
    }

    /**
     * 获取已安装插件列表
     * @return array Format: [['name' => 'name', 'value' => ['display_name' => 'display_name', 'route' => 'route']]]
     * @throws \yii\db\StaleObjectException
     */
    public static function getInstallPluginList()
    {
        if (static::$installedPluginList) {
            return static::$installedPluginList;
        }
        static::$installedPluginList = [];
        /** @var Plugin[] $list */
        $list = Plugin::find()->all();
        $pluginNames = [];
        foreach ($list as $item) {
            if (!$item->name || !$item->display_name || !$item->route) {
                $jsonData = \Yii::$app->security->decryptByPassword(base64_decode($item->data), static::DECODE_KEY);
                $data = json_decode($jsonData, true);
                if (!$data) {
                    continue;
                }
                $item->name = $data['name'];
                $item->display_name = $data['value']['display_name'];
                $item->route = $data['value']['route'];
                $item->addtime = time();
                $item->save();
            }
            if (in_array($item->name, $pluginNames)) {
                $item->delete();
                continue;
            }
            static::$installedPluginList[] = [
                'name' => $item->name,
                'value' => [
                    'display_name' => $item->display_name,
                    'route' => $item->route,
                ],
            ];
            $pluginNames[] = $item->name;
        }
        return static::$installedPluginList;
    }

    /**
     * 查询某个插件是否已安装
     * @param $name
     * @return bool
     * @throws \yii\db\StaleObjectException
     */
    public static function isPluginInstalled($name)
    {
        $installedList = static::getInstallPluginList();
        foreach ($installedList as $item) {
            if ($item['name'] === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @param array $data
     * @return mixed
     * @throws CloudException
     */
    private static function sendRequest($method, $url, $params = [], $data = [])
    {
        if ($method === static::REQUEST_METHOD_GET) {
            $response = HttpClient::get($url, $params);
        } elseif ($method === static::REQUEST_METHOD_POST) {
            $response = HttpClient::post($url, $data, $params);
        } else {
            throw new \Exception('系统错误：无效的method');
        }
        $res = json_decode($response, true);
        if (!$res) {
            throw new \Exception('网络错误：' . $response);
        }
        if (isset($res['request'])) {
            unset($res['request']);
        }
        return $res;
    }
}
