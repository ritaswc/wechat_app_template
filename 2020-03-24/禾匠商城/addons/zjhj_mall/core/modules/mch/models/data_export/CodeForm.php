<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/20
 * Time: 10:54
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\modules\mch\models\data_export;


use app\hejiang\ApiCode;
use app\models\Option;
use app\modules\mch\models\MchModel;

class CodeForm extends MchModel
{
    public function search()
    {
        $token = Option::get('export_token', $this->store->id, 'mall', null);
        if (!$token) {
            $token = $this->getToken();
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '1',
            'data' => [
                'code' => $this->getCode($token),
            ]
        ];
    }

    public function reset()
    {
        $token = $this->getToken();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '2',
            'data' => [
                'code' => $this->getCode($token)
            ]
        ];
    }

    protected function getCode($token)
    {
        $list = [
            'api' => \Yii::$app->urlManager->createAbsoluteUrl(['export/index']),
            'params' => [
                'token' => $token,
                'store_id' => $this->store->id
            ]
        ];
        $code = base64_encode(json_encode($list, JSON_UNESCAPED_UNICODE));
        return $code;
    }

    protected function getToken()
    {
        $token = \Yii::$app->security->generateRandomString();
        Option::set('export_token', $token, $this->store->id, 'mall');
        return $token;
    }
}
