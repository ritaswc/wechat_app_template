<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/11
 * Time: 10:46
 */

namespace app\modules\mch\models\order;


use app\modules\mch\models\MchModel;
use yii\db\ActiveRecord;

/**
 * @property ActiveRecord $order_model
 */
class OrderDeleteForm extends MchModel
{
    public $order_model;

    public $order_id;
    public $store;

    public $is_recycle;
    public $type;
    public $mch_id;

    public function delete()
    {
        if (!$this->order_id) {
            return [
                'code' => 1,
                'msg' => '数据错误，请刷新后重试'
            ];
        }
        $orderClass = $this->order_model;
        $order = $orderClass::find()->where(['id' => $this->order_id])->andWhere(['is_recycle'=>1])->one();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新后重试'
            ];
        }
        if (isset($order->is_recycle)) {
            if ($order->is_recycle == 0) {
                $order->is_recycle = 1;
            } else {
                if (isset($order->is_show)) {
                    $order->is_show = 0;
                } else {
                    return [
                        'code' => 1,
                        'msg' => "操作失败，{$orderClass}缺少is_show字段"
                    ];
                }
            }
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少is_recycle字段"
            ];
        }
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '操作成功'
            ];
        } else {
            $this->getErrorResponse($order);
        }
    }

    // 清空回收站
    public function deleteAll()
    {
        $orderClass = $this->order_model;
        $condition = ['is_recycle' => 1, 'store_id' => $this->store->id];
        if ($this->type || $this->type === 0) {
            $condition['type'] = $this->type;
        }
        if ($this->mch_id === 0) {
            $condition['mch_id'] = 0;
        }
        if ($this->mch_id) {
            $condition = ['and', $condition, ['>', 'mch_id', 0]];
        }
        $count = $orderClass::updateAll(['is_show' => 0], $condition);
        return [
            'code' => 0,
            'msg' => "已清空，共删除{$count}个订单"
        ];
    }

    // 移入移出回收站
    public function recycle()
    {
        if (!$this->order_id) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新后重试'
            ];
        }
        $orderClass = $this->order_model;
        $condition = ['store_id' => $this->store->id, 'id' => $this->order_id];
        if($this->is_recycle && $this->is_recycle == 1){
            $condition['is_recycle'] = 0;
        }else{
            $condition['is_recycle'] = 1;
        }
        $order = $orderClass::find()->where($condition)->one();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新后重试'
            ];
        }
        if (isset($order->is_recycle)) {
            $order->is_recycle = $this->is_recycle;
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少is_recycle字段"
            ];
        }
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '操作成功'
            ];
        } else {
            return $this->getErrorResponse($order);
        }
    }
}