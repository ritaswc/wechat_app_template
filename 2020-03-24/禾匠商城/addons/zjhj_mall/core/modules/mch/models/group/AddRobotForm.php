<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/3/2
 * Time: 14:43
 */

namespace app\modules\mch\models\group;

use app\models\PtNoticeSender;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtRobot;
use app\modules\mch\models\MchModel;

class AddRobotForm extends MchModel
{
    public $store_id;
    public $user_id;

    public $r_id;
    public $goods_id;
    public $p_id;

    public function save()
    {
        $robot = PtRobot::find()->andWhere(['id'=>$this->r_id])->one();

        $class_group = PtOrder::findOne([
            'id' => $this->p_id
        ])->class_group;

        $t = \Yii::$app->db->beginTransaction();
        $ptOrder = new PtOrder();
        $ptOrder->store_id = $this->store_id;
        $ptOrder->user_id = $robot->id;
        $ptOrder->order_no = "robot";
        $ptOrder->total_price = 0;
        $ptOrder->pay_price = 0;
        $ptOrder->name = $robot->name;
        $ptOrder->mobile = '0010';
        $ptOrder->address = '机器人赛尔号x星球';
        $ptOrder->remark = '';
        $ptOrder->is_pay = 1;
        $ptOrder->pay_type = 1;
        $ptOrder->pay_time = time();
        $ptOrder->is_send = 1;
        $ptOrder->send_time = time();
        $ptOrder->express = 'test';
        $ptOrder->express_no = '0010';
        $ptOrder->is_confirm = 1;
        $ptOrder->confirm_time = time();
        $ptOrder->is_comment = 1;
        $ptOrder->apply_delete = 0;
        $ptOrder->addtime = time();
        $ptOrder->is_delete = 0;
        $ptOrder->address_data = '';
        $ptOrder->is_group = 1;
        $ptOrder->parent_id = $this->p_id;
        $ptOrder->colonel = 0;
        $ptOrder->is_success = 0;
        $ptOrder->success_time = 0;
        $ptOrder->status = 2;
        $ptOrder->is_returnd = 0;
        $ptOrder->is_cancel = 0;
        $ptOrder->limit_time = 0;
        $ptOrder->content = '';
        $ptOrder->words = '';
        $ptOrder->class_group = $class_group;
        if ($ptOrder->save()) {
            $order_detail = new PtOrderDetail();
            $order_detail->order_id = $ptOrder->id;
            $order_detail->goods_id = $this->goods_id;
            $order_detail->num = 1;
            $order_detail->total_price = 0;
            $order_detail->addtime = time();
            $order_detail->is_delete = 0;
            $order_detail->attr = '{}';
            $order_detail->pic = 'pic';
            if (!$order_detail->save()) {
                $t->rollBack();
                return [
                    'code'  => 1,
                    'msg'   => '订单提交失败，请稍后再重试',
                    'data'  => $this->getErrorResponse($order_detail),
                ];
            }
            $t->commit();
            if ($ptOrder->getSurplusGruop()<=0) {
                $orderList = PtOrder::find()
                    ->andWhere(['or',['id'=>$this->p_id],['parent_id'=>$this->p_id]])
                    ->andWhere(['status'=>2,'is_group'=>1])
                    ->andWhere([
                        'OR',
                        ['is_pay'=>1],
                        ['pay_type'=>2]
                    ])
                    ->all();
                foreach ($orderList as $val) {
                    $val->is_success = 1;
                    $val->success_time = time();
                    $val->status = 3;
                    $val->save();
                }
                $notice = new PtNoticeSender($this->getWechat(), $ptOrder->store_id);
                $notice->sendSuccessNotice($ptOrder->id);
            }

            return [
                'code' => 0,
                'msg' => '添加机器人成功',
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($ptOrder);
        }
    }

//    /**
//     * @return string
//     * 拼团剩余人数
//     */
//    public function getSurplusGruop($order_id)
//    {
//        $order_detail = PtOrderDetail::findOne(['order_id'=>$order_id,'is_delete'=>0]);
//        $goods = PtOrder::findOne(['id'=>$order_detail->goods_id]);
//        $groupNum = PtOrder::find()
//            ->andWhere(['or',['id'=>$order_id],['parent_id'=>$order_id]])
//            ->andWhere(['status'=>2,'is_pay'=>1,'is_group'=>1])
//            ->count();
//
//        return $goods->group_num - $groupNum;
//    }
}
