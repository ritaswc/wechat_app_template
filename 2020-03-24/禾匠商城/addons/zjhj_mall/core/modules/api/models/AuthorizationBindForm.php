<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\api\models;


use app\models\Option;
use app\models\User;

class AuthorizationBindForm extends ApiModel
{
    public function bind()
    {

        $user = User::findOne($this->getCurrentUserId());

        if (empty($user->wechat_union_id)) {
            return [
                'code' => 1,
                'msg' => 'union_id 为空,请检查小程序和公众号是否绑定在同一开放平台'
            ];
        }

        $data = [
            'bind_url' => $this->getAdminUrl('bind')
        ];

        return [
            'code' => 0,
            'data' => $data,
            'msg' => '请求成功'
        ];
    }

    public function checkBind()
    {
        $user = User::findOne($this->getCurrentUserId());
        $option = Option::get('BIND_WECHAT_PLATFORM', $this->getCurrentStoreId());

        if (!empty($user->wechat_platform_open_id)) {
            $title = '您已成功绑定' . $option['app_name'] . '微信公众号';
            $option['title'] = $title;
            return [
                'code' => 0,
                'data' => [
                    'is_bind' => 1,
                    'app' => $option,
                ],
            ];
        }

        $title = '确认绑定' . $option['app_name'] . '微信公众号？';
        $option['title'] = $title;
        return [
            'code' => 0,
            'data' => [
                'is_bind' => 2,
                'app' => $option,
            ],
        ];

    }
}