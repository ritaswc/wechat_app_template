<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/19
 * Time: 11:34
 */

namespace app\models\task\order;


use app\hejiang\task\TaskRunnable;
use app\models\ActionLog;
use app\models\IntegralOrder;
use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\Store;
use app\utils\PinterOrder;
use app\utils\TaskCreate;

/**
 * @property Store $store
 */
class OrderAuthConfirm extends TaskRunnable
{
    const STORE = 'STORE';
    const MS = 'MIAOSHA';
    const PT = 'PINTUAN';
    const INTEGRAL = 'INTEGRAL';

    public $store;
    public $time;
    public $params = [];
    public $actionType;
    /* @var $order Order|PtOrder|MsOrder|IntegralOrder */
    public $order;
    public $orderType;

    /**
     * @param array $param
     * @return bool
     * @throws \Exception
     */
    public function run($param = [])
    {
        $this->store = Store::findOne($param['store_id']);
        \Yii::$app->store = $this->store;
        $this->time = time() - ($this->store->delivery_time * 86400);
        $this->params = $param;

        switch ($param['order_type']) {
            case self::STORE:
                $this->orderType = 0;
                $this->order = Order::findOne([
                    'id' => $this->params['order_id'],
                    'is_pay' => Order::IS_PAY_TRUE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_TRUE,
                ]);
                if (!$this->order) {
                    ActionLog::addTask(['message' => '商成订单不存在'], $this->order->store_id, $this->actionType, $this->order->id);
                    return true;
                }
                switch ($this->order->type) {
                    case 0:
                        $this->actionType = "商城订单自动确认收货";
                        break;
                    case 1:
                        $this->actionType = "砍价订单自动确认收货";
                        break;
                    case 2:
                        $this->actionType = "九宫格订单自动确认收货";
                        break;
                    case 3:
                        $this->actionType = "刮刮卡订单自动确认收货";
                        break;
                    case 4:
                        $this->actionType = "抽奖订单自动确认收货";
                        break;
                }
                if ($this->order->is_confirm == Order::IS_CONFIRM_TRUE) {
                    ActionLog::addTask(['message' => '订单已收货'], $this->order->store_id, $this->actionType, $this->order->id);
                    return true;
                }
                break;
            case self::MS:
                $this->actionType = "秒杀订单自动确认收货";
                $this->orderType = 1;
                $this->order = MsOrder::findOne([
                    'id' => $this->params['order_id'],
                    'is_pay' => Order::IS_PAY_TRUE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_TRUE,
                ]);
                break;
            case self::PT:
                $this->actionType = "拼团订单自动确认收货";
                $this->orderType = 2;
                $this->order = PtOrder::findOne([
                    'id' => $this->params['order_id'],
                    'is_pay' => Order::IS_PAY_TRUE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_TRUE,
                ]);
                break;
            case self::INTEGRAL:
                $this->actionType = "积分商城订单自动确认收货";
                $this->orderType = 4;
                $this->order = IntegralOrder::findOne([
                    'id' => $this->params['order_id'],
                    'is_pay' => Order::IS_PAY_TRUE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_TRUE,
                ]);
                break;
            default:
                return true;
        }
        if (!$this->order) {
            ActionLog::addTask(['message' => '订单不存在'], $this->order->store_id, $this->actionType, $this->order->id);
            return true;
        }
        if ($this->order->is_confirm == Order::IS_CONFIRM_TRUE) {
            ActionLog::addTask(['message' => '订单已收货'], $this->order->store_id, $this->actionType, $this->order->id);
            return true;
        }

        $res = $this->exe();
        return $res;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    private function exe()
    {
        $order = $this->order;
        $t = \Yii::$app->db->beginTransaction();
        try {
            $order->is_confirm = 1;
            $order->confirm_time = time();
            if ($order->save()) {
                $t->commit();
                $printerOrder = new PinterOrder($this->order->store_id, $this->order->id, 'confirm', $this->orderType);
                $res = $printerOrder->print_order();
                return true;
            } else {
                $t->rollBack();
                ActionLog::addTask($order->errors, $this->order->store_id, $this->actionType, $this->order->id);
                throw new \Exception('确认收货失败');
            }
        } catch (\Exception $e) {
            $t->rollBack();
            $errorInfo = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ];
            ActionLog::addTask($errorInfo, $this->order->store_id, $this->actionType, $this->order->id);
            throw $e;
        }
    }
}
