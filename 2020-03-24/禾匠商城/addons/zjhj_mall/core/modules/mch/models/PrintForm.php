<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 15:22
 */

namespace app\modules\mch\models;

use app\utils\KdOrder;
use app\models\Delivery;
use app\models\Express;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderExpress;
use app\models\Sender;
use app\models\Store;
use app\modules\mch\models\integralmall\IntegralOrderForm;
use app\modules\mch\models\miaosha\OrderListForm;
use yii\helpers\VarDumper;
use app\models\IntegralOrder;

class PrintForm extends MchModel
{
    public $order_id;
    public $store_id;
    public $express;
    public $post_code;
    public $order_type;

    public function send()
    {
        if (!$this->express) {
            return [
                'code' => 1,
                'msg' => '请选择快递公司'
            ];
        }
        if ($this->order_type == 1) {
            $order_type = 1;
            $order = MsOrder::findOne(['id' => $this->order_id]);
        } elseif ($this->order_type == 'IN') {
            $order = IntegralOrder::findOne(['id' => $this->order_id]);
        } else {
            $order_type = 0;
            $order = Order::findOne([
                'id' => $this->order_id,
                'mch_id' => 0,
            ]);
            if (!$order) {
                return [
                    'code' => 1,
                    'msg' => '订单错误'
                ];
            }
        }

//        $express = Express::findOne(['name'=>$this->express]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单错误'
            ];
        }

        $express_afters = [
            '快递',
            '快运',
            '物流',
            '速运',
        ];
//        $express = Express::find()->orderBy('sort')->where([
//            'name' => $this->express,
//        ])->one();
//        if(!$express){
//            foreach ($express_afters as $after) {
//                $express = str_replace($after, '', $express);
//            }
//            $express = Express::find()->orderBy('sort')->where(['LIKE', 'name', $express,])->one();
//        }
//        if(!$express){
//            return [
//                'code'=>1,
//                'msg'=>'快递公司不存在'
//            ];
//        }
        $express = Express::getOne($this->express);
        if (!$express) {
            return [
                'code' => 1,
                'msg' => '快递公司不正确'
            ];
        }
        $store = Store::findOne(['id' => $this->store_id]);
        $orderExpressList = OrderExpress::find()->where([
            'store_id' => $this->store_id, 'EBusinessID' => $store->kdniao_mch_id,
            'order_id' => $order->id, 'order_type' => $order_type])
            ->all();
        foreach ($orderExpressList as $orderExpress) {
            if ($orderExpress->express_code == $express['code']) {
                $result = [
                    'EBusinessID' => $orderExpress->EBusinessID,
                    'Order' => json_decode($orderExpress->order, true),
                    'PrintTemplate' => $orderExpress->printTeplate
                ];
                return [
                    'code' => 0,
                    'msg' => '成功',
                    'data' => $result
                ];
            }
        }
        if (!empty($orderExpressList)) {
            $orderCode = $order->order_no . "-" . count($orderExpressList);
        } else {
            $orderCode = $order->order_no;
        }
//构造电子面单提交信息
        $eorder = [];
        $delivery = Delivery::findOne(['express_id' => $express['id'], 'is_delete' => 0, 'store_id' => $this->store_id]);
        if (!$delivery) {
            $delivery_id = 0;
            $pay_type = 1;
        } else {
            $delivery_id = $delivery->id;
            $pay_type = 3;
            $eorder['CustomerName'] = $delivery->customer_name;
            $eorder['CustomerPwd'] = $delivery->customer_pwd;
            $eorder['SendSite'] = $delivery->send_site;
            $eorder['MonthCode'] = $delivery->month_code;
            if ($delivery->template_size) {
                $eorder["TemplateSize"] = $delivery->template_size;
            }
        }
        $sender_list = Sender::findOne(['delivery_id' => $delivery_id, 'is_delete' => 0, 'store_id' => $this->store_id]);
        if (!$sender_list) {
            return [
                'code' => 1,
                'msg' => '请先设置发件人信息'
            ];
        }
        $eorder["ShipperCode"] = $express['code'];
        $eorder["OrderCode"] = $orderCode;
        $eorder["PayType"] = $pay_type;
        $eorder["ExpType"] = 1;
        $eorder["IsReturnPrintTemplate"] = 1;
        $eorder["IsSendMessage"] = 1;

        $sender = [];
        $sender["Company"] = $sender_list->company;
        $sender["Name"] = $sender_list->name;
        $sender["Mobile"] = $sender_list->mobile ? $sender_list->mobile : $sender_list->tel;
        $sender["ProvinceName"] = $sender_list->province;
        $sender["CityName"] = $sender_list->city;
        $sender["ExpAreaName"] = $sender_list->exp_area;
        $sender["Address"] = $sender_list->address;
        $sender["PostCode"] = $sender_list->post_code;

        $receiver = [];
        $receiver["Name"] = $order->name;
        $receiver["Mobile"] = $order->mobile;
        $address = ['province' => '空', 'city' => '空', 'district' => '空', 'detail' => $order->address];
        $receiver_address = $order->address_data ? json_decode($order->address_data, true) : $address;
        $receiver["ProvinceName"] = $receiver_address['province'];
        $receiver["CityName"] = $receiver_address['city'];
        $receiver["ExpAreaName"] = $receiver_address['district'];
        $receiver["Address"] = str_replace(PHP_EOL, '', $receiver_address['detail']);
        $receiver["PostCode"] = $this->post_code;

        if ($order_type == 0) {
            $form = new \app\modules\mch\models\OrderListForm();
            $good_list = $form->getOrderGoodsList($order->id);
        }
        if ($order_type == 1) {
            $form = new OrderListForm();
            $good_list = $form->getOrderGoodsList($order->id);
        }
        if ($this->order_type == 'IN') {
            $form = new IntegralOrderForm();
            $good_list = $form->getOrderGoodsList($order->id);
        }
        $good = $good_list[0];
        $commodity = [];
        // TODO 商品信息暂时不显示
        foreach ($good_list as $index => $good) {
            $commodityOne = [];
            $desc = "";
            foreach ($good['attr_list'] as $key => $value) {
                $desc .= ',';
                $desc .= $value['attr_group_name'] . ':' . $value['attr_name'];
            }
//            $commodityOne["GoodsName"] = $good['name'] . '，数量：' . intval($good['num']) . $desc;
            $commodityOne["GoodsName"] = '商品';
            $commodityOne["GoodsCode"] = "";
            $commodityOne["Goodsquantity"] = "";
            $commodityOne["GoodsPrice"] = "";
            $commodityOne["GoodsWeight"] = "";
            $commodityOne['GoodsDesc'] = "";
            $commodityOne['GoodsVol'] = "";
            $commodity[] = $commodityOne;
        }

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;


//调用电子面单
        $jsonParam = \Yii::$app->serializer->encode($eorder);

//$jsonParam = JSON($eorder);//兼容php5.2（含）以下

//        echo "电子面单接口提交内容：<br/>".$jsonParam;
        $jsonResult = KdOrder::submitEOrder($jsonParam, $this->store_id);
//        echo "<br/><br/>电子面单提交结果:<br/>".$jsonResult;

//解析电子面单返回结果
        $result = json_decode($jsonResult, true);
//        echo "<br/><br/>返回码:".$result["ResultCode"];
        if ($result["ResultCode"] == "100" || $result["ResultCode"] == '106') {
//            echo "<br/>是否成功:".$result["Success"];
            $form = new OrderExpress();
            $form->store_id = $this->store_id;
            $form->order_id = $order->id;
            $form->EBusinessID = $result['EBusinessID'];
            $form->order = \Yii::$app->serializer->encode($result['Order']);
            $form->printTeplate = $result['PrintTemplate'];
            $form->is_delete = 0;
            $form->order_type = $order_type;
            $form->express_code = $express['code'];
            $form->save();
            return [
                'code' => 0,
                'msg' => '成功',
                'data' => $result
            ];
        } else {
//            echo "<br/>电子面单下单失败";
            return [
                'code' => 1,
                'msg' => $result['Reason'],
                'data' => $result
            ];
        }
    }

    private function getExpressCode($express, $type)
    {
        $express_afters = [
            '快递',
            '快运',
            '物流',
            '速运',
        ];
        $express = Express::find()->orderBy('sort')->where([
            'name' => $express,
            'type' => $type,
        ])->one();
        if ($express) {
            return $express->code;
        }
        foreach ($express_afters as $after) {
            $express = str_replace($after, '', $express);
        }
        $express = Express::find()->orderBy('sort')->where([
            'AND',
            ['LIKE', 'name', $express,],
            ['type' => $type,]
        ])->one();
        if ($express) {
            return $express;
        }
        return '';
    }
}
