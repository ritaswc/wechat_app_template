<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3
 * Time: 15:35
 */

namespace app\modules\user\models\mch;

use app\models\Express;
use app\modules\user\models\UserModel;
use app\models\Order;
use app\models\WechatTplMsgSender;
use app\utils\TaskCreate;

class OrderSendForm extends UserModel
{

    public $store_id;
    public $mch_id;
    public $order_id;
    public $express;
    public $express_no;
    public $words;

    public function rules()
    {
        return [
            [['express', 'express_no','words'], 'trim'],
            [['express', 'express_no',], 'required','on'=>'EXPRESS'],
            [['order_id'],'required'],
            [['express', 'express_no',], 'string',],
            [['express', 'express_no',], 'default','value'=>''],
        ];
    }

    public function attributeLabels()
    {
        return [
            'express' => '快递公司',
            'express_no' => '快递单号',
            'words' => '商家留言',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = Order::findOne([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'id' => $this->order_id,
            'mch_id'=>$this->mch_id
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在或已删除',
            ];
        }
        if ($order->is_pay == 0 && $order->pay_type != 2) {
            return [
                'code'=>1,
                'msg'=>'订单未支付'
            ];
        }
        $exportList = Express::getExpressList();
        $ok = false;
        foreach ($exportList as $value) {
            if ($value['name'] == $this->express) {
                $ok = true;
                break;
            }
        }
        if (!$ok && $this->scenario == "EXPRESS") {
            return [
                'code'=>1,
                'msg'=>'快递公司不正确'
            ];
        }
        $order->express = $this->express;
        $order->express_no = $this->express_no;
        $order->words = $this->words;
        $order->is_send = 1;
        $order->send_time = time();
        if ($order->save()) {
            try {
                $wechat_tpl_meg_sender = new WechatTplMsgSender($this->store_id, $order->id, $this->getWechat());
                $wechat_tpl_meg_sender->sendMsg();
                TaskCreate::orderConfirm($order->id, 'STORE');
            } catch (\Exception $e) {
                \Yii::warning($e->getMessage());
            }
            return [
                'code' => 0,
                'msg' => '发货成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }
}
