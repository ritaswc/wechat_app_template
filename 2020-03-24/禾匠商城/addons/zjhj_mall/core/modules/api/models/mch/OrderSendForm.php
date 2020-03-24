<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/20
 * Time: 10:39
 */


namespace app\modules\api\models\mch;


use app\models\Express;
use app\models\Order;
use app\modules\api\models\ApiModel;
use app\utils\TaskCreate;

class OrderSendForm extends ApiModel
{
    public $mch_id;
    public $order_id;
    public $send_type;

    public $express;
    public $express_no;
    public $words;

    public function rules()
    {
        return [
            [['order_id', 'send_type',], 'required'],
            [['express', 'express_no', 'words'], 'trim'],
            [['send_type',], 'in', 'range' => ['express', 'none'],],
            [['express'], 'string', 'max' => 20],
            [['express_no'], 'string', 'max' => 20],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return $this->errorResponse;
        $order = Order::findOne([
            'id' => $this->order_id,
            'mch_id' => $this->mch_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在。',
            ];
        }
        if ($order->is_confirm == 1) {
            return [
                'code' => 1,
                'msg' => '订单已确认收货无法再修改物流信息。',
            ];
        }
        if ($this->send_type == 'express') {
            if(!$this->express || !$this->express_no){
                return [
                    'code' => 1,
                    'msg' => '请填写快递公司和快递单号。',
                    'a' => $this->attributes,
                ];
            }
            $expressList = Express::getExpressList();
            $ok = false;
            foreach($expressList as $value){
                if($value['name'] == $this->express){
                    $ok = true;
                    break;
                }
            }
            if(!$ok){
                return [
                    'code'=>1,
                    'msg'=>'快递公司不正确'
                ];
            }
        }
        $order->express = $this->express;
        $order->express_no = $this->express_no;
        $order->words = $this->words;
        if ($order->is_send == 0) {
            $order->is_send = 1;
            $order->send_time = time();
        }
        $order->save();
        TaskCreate::orderConfirm($order->id, 'STORE');
        return [
            'code' => 0,
            'msg' => '发货信息提交成功。'
        ];

    }
}
