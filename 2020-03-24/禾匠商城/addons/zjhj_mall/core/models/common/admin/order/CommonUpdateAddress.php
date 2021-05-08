<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\admin\order;


use app\models\IntegralOrder;
use app\models\Model;
use app\models\MsOrder;
use app\models\Option;
use app\models\Order;
use app\models\PtOrder;

class CommonUpdateAddress
{
    public $data;//更新的数据

    public function updateAddress()
    {
        $orderType = $this->data['orderType'];
        $orderId = $this->data['orderId'];
        $name = $this->data['name'];
        $mobile = $this->data['mobile'];
        $address = $this->data['address'];
        $province = $this->data['province'];
        $city = $this->data['city'];
        $district = $this->data['district'];

        if (!isset($orderType)) {
            return [
                'code' => 1,
                'msg' => '请传入订单类型'
            ];
        }
        if (!isset($orderId)) {
            return [
                'code' => 1,
                'msg' => '请传入订单ID'
            ];
        }
        if (!isset($name)) {
            return [
                'code' => 1,
                'msg' => '请填写收件人姓名'
            ];
        }

        $option = Option::getList('mobile_verify', \Yii::$app->controller->store->id, 'admin', 1);
        if ($option['mobile_verify']) {
            if (!preg_match(Model::MOBILE_VERIFY, $mobile)) {
                return [
                    'code' => 1,
                    'msg' => '请输入正确的手机号'
                ];
            }
        }

        if (!isset($mobile)) {
            return [
                'code' => 1,
                'msg' => '请填写收件人手机号'
            ];
        }
        if (!isset($address)) {
            return [
                'code' => 1,
                'msg' => '请填写收件人地址'
            ];
        }

        if ($orderType === 'store') {
            $order = Order::findOne($orderId);
        } else if ($orderType === 'integral') {
            $order = IntegralOrder::findOne($orderId);
        } else if ($orderType === 'pintuan') {
            $order = PtOrder::findOne($orderId);
        } else if ($orderType === 'miaosha') {
            $order = MsOrder::findOne($orderId);
        } else {
            return [
                'code' => 1,
                'msg' => '未知的订单类型,请检查'
            ];
        }

        $arr = [
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'detail' => $address
        ];

        $order->name = $name;
        $order->mobile = $mobile;
        $order->address = $province . $city . $district . $address;
        $order->address_data = \Yii::$app->serializer->encode($arr);

        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }

        return [
            'code' => 1,
            'msg' => '更新失败'
        ];
    }
}