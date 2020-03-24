<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25
 * Time: 16:00
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\IntegralGoods;
use app\models\IntegralOrderDetail;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\PtGoods;
use app\models\PtOrderDetail;
use app\modules\mch\extensions\Export;
use yii\db\Query;

class ExportList
{
    public $order_type = 0; // 0--商城 1--秒杀 2--拼团 3--预约 4--积分商城 5--分销

    public $is_offline; // 0--非自提 1--自提
    public $fields;
    public $type; // 0--非售后 1--售后


    public function getList()
    {
        $list = [
            [
                'key' => 'order_no',
                'value' => '订单号',
                'hidden' => false,
                'selected' => 0,
                'type' => [0, 1],
            ],
            [
                'key' => 'nickname',
                'value' => '下单用户',
                'hidden' => false,
                'selected' => 0,
                'type' => [0, 1],
            ],
            [
                'key' => 'good_name',
                'value' => '商品名',
                'hidden' => false,
                'selected' => 0,
                'type' => [0, 1],
            ],
            [
                'key' => 'attr',
                'value' => '规格',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0, 1],
            ],
            [
                'key' => 'good_num',
                'value' => '数量',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0, 1],
            ],
            [
                'key' => 'good_no',
                'value' => '货号',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0, 1],
            ],
            [
                'key' => 'name',
                'value' => '收件人',
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0, 1],
            ],
            [
                'key' => 'mobile',
                'value' => '收件人电话',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0, 1],
            ],
            [
                'key' => 'address',
                'value' => '收件人地址',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0, 1],
            ],
            [
                'key' => 'cost_price',
                'value' => '成本价',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0],
                'type' => [0],
            ],
            [
                'key' => 'total_price',
                'value' => '总金额',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'pay_price',
                'value' => '实际付款',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'integral',
                'value' => '积分',
                'hidden' => false,
                'selected' => 0,
                'type' => [4],
            ],
            [
                'key' => 'express_price',
                'value' => '运费',
                'hidden' => true,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0],
            ],
            [
                'key' => 'express_no',
                'value' => '快递单号',
                'hidden' => true,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0],
            ],
            [
                'key' => 'express',
                'value' => '快递公司',
                'hidden' => true,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0],
            ],
            [
                'key' => 'clerk_name',
                'value' => '核销人',
                'hidden' => true,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'shop_name',
                'value' => '核销门店',
                'hidden' => true,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'addtime',
                'value' => '下单时间',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'pay_type',
                'value' => '支付方式',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'order_status',
                'value' => '订单状态',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'status',
                'value' => '拼团状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [2],
                'type' => [0],
            ],
            [
                'key' => 'is_pay',
                'value' => '付款状态',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'pay_time',
                'value' => '付款时间',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'apply_delete',
                'value' => '申请状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0],
            ],
            [
                'key' => 'is_send',
                'value' => '发货状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0],
            ],
            [
                'key' => 'send_time',
                'value' => '发货时间',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0],
            ],
            [
                'key' => 'is_confirm',
                'value' => '收货状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0],
            ],
            [
                'key' => 'confirm_time',
                'value' => '收货时间',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [0],
            ],
            [
                'key' => 'content',
                'value' => '备注/表单',
                'hidden' => false,
                'selected' => 0,
                'type' => [0],
            ],
            [
                'key' => 'words',
                'value' => '买家留言',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [0],
            ],
            [
                'key' => 'is_use',
                'value' => '使用状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [3],
                'type' => [0],
            ],
            [
                'key' => 'use_time',
                'value' => '使用时间',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [3],
                'type' => [0],
            ],
            [
                'key' => 'refund_type',
                'value' => '售后类型',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [1],
            ],
            [
                'key' => 'refund_price',
                'value' => '退款金额',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [1],
            ],
            [
                'key' => 'refund_desc',
                'value' => '申请理由',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [1],
            ],
            [
                'key' => 'refund_status',
                'value' => '状态',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [1],
            ],
            [
                'key' => 'refund_refuse_desc',
                'value' => '售后拒绝理由',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [1],
            ],
            [
                'key' => 'refund_time',
                'value' => '售后申请时间',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4],
                'type' => [1],
            ],
            [
                'key' => 'user_send_express',
                'value' => '用户发货快递公司',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [1],
            ],
            [
                'key' => 'user_send_express_no',
                'value' => '用户发货快递单号',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 4, 5],
                'type' => [1],
            ],
            [
                'key' => 'rebate',
                'value' => '自购返利',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [5],
                'type' => [0, 1],
            ],
            [
                'key' => 'share_commission_first',
                'value' => '一级分销商',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [5],
                'type' => [0, 1],
            ],
            [
                'key' => 'share_commission_second',
                'value' => '二级分销商',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [5],
                'type' => [0, 1],
            ],
            [
                'key' => 'share_commission_third',
                'value' => '三级分销商',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [5],
                'type' => [0, 1],
            ],
            [
                'key' => 'merchant_remark',
                'value' => '商家备注',
                'hidden' => false,
                'selected' => 0,
                'order_type' => [0, 1, 2, 3],
                'type' => [0, 1],
            ],

        ];
        foreach ($list as $i => $item) {
            if (isset($item['order_type']) && !in_array($this->order_type, $item['order_type'])) {
                unset($list[$i]);
                continue;
            }
            if (isset($item['type']) && !in_array($this->type, $item['type'])) {
                unset($list[$i]);
                continue;
            }
        }

        return $list;
    }

    /**
     * @param $data array 需要处理的数据
     */
    public function dataTransform($data)
    {
        $newFields = [];
        $pay_type_list = ['线上支付', '线上支付', '货到付款', '余额支付'];
        foreach ($this->fields as &$item) {
            if ($this->is_offline == 1) {
                if (in_array($item['key'], ['clerk_name', 'shop_name'])) {
                    $item['selected'] = 1;
                }
            } else {
                if (in_array($item['key'], ['express_price', 'express_no', 'express'])) {
                    $item['selected'] = 1;
                }
            }
            if (isset($item['selected']) && $item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $newList = [];
        foreach ($data as $datum) {
            $newItem = [];
            $newItem['order_no'] = $datum->order_no;
            $newItem['nickname'] = $datum->user->nickname;
            $newItem['platform'] = $datum->user->platform ? '支付宝' : '微信';
            $newItem['total_price'] = $datum->total_price;
            $newItem['pay_price'] = $datum->pay_price;
            $newItem['pay_type'] = $pay_type_list[$datum->pay_type];
            $newItem['is_pay'] = $datum['is_pay'] == 1 ? "已付款" : "未付款";
            $newItem['addtime'] = date('Y-m-d H:i', $datum['addtime']);
            $newItem['pay_time'] = $datum->pay_time ? date('Y-m-d H:i', $datum->pay_time) : '';

            //是否到店自提 0--否 1--是
            if ((isset($datum['is_offline']) && $datum['is_offline']) || (isset($datum['offline']) && $datum['offline'] == 2) || $this->order_type == 3) {
                $newItem['clerk_name'] = $datum->clerk ? $datum->clerk->nickname : '';
                $newItem['shop_name'] = $datum->shop ? $datum->shop->name : '';
            } else {
                $newItem['express_price'] = $datum->express_price;
                $newItem['express_no'] = $datum->express_no;
                $newItem['express'] = $datum->express;
            }

            if (in_array($this->order_type, [0, 1, 2])) {
                $newItem['name'] = $datum->name;
                $newItem['mobile'] = $datum->mobile;
                $newItem['address'] = $datum->address;
                $newItem['apply_delete'] = ($datum['apply_delete'] == 1) ? ($datum['is_cancel'] == 1 || $datum['is_delete'] == 1 ? '取消成功' : '取消中') : "无";
                $newItem['is_send'] = ($datum['is_send'] == 1) ? "已发货" : "未发货";
                $newItem['is_confirm'] = ($datum['is_confirm'] == 1) ? "已收货" : "未收货";
                $newItem['send_time'] = $datum->send_time ? date('Y-m-d H:i', $datum->send_time) : '';
                $newItem['confirm_time'] = $datum->confirm_time ? date('Y-m-d H:i', $datum->confirm_time) : '';
                $newItem['words'] = isset($datum->words) ? $datum->words : '';
                $newItem['goods_list'] = $this->getGoodsList($datum->id);
            }
            if ($this->order_type == 0) {
                if ($datum->orderForm) {
                    $str = '';
                    foreach ($datum->orderForm as $key => $item) {
                        $str .= $item['key'] . ':' . $item['value'] . ',';
                    }
                    $newItem['content'] = rtrim($str, ',');
                } else {
                    $newItem['content'] = $datum->content;
                }
            } else {
                $newItem['content'] = $datum->content;
            }
            if ($this->order_type == 2) {
                $status = ['', '待付款', '拼团中', '拼团成功', '拼团失败'];
                $newItem['status'] = $status[$datum['status']];
                $newItem['content'] = $datum->content;
            }
            if ($this->order_type == 3) {
                $str = '';
                if (is_array($datum->orderForm)) {
                    foreach ($datum->orderForm as $key => $item) {
                        $str .= $item['key'] . ':' . $item['value'] . ',';
                    }
                    $str = rtrim($str, ',');
                }
                $newItem['content'] = $str;
                $newItem['goods_list'] = $datum->goods;
                $newItem['is_use'] = ($datum->is_use == 1) ? '已使用' : '未使用';
                $newItem['use_time'] = $datum->use_time ? date('Y-m-d H:i', $datum->use_time) : 0;
            }

            $newList[] = $newItem;
        }
        Export::order_3($newList, $newFields);
    }

    private function getGoodsList($order_id)
    {
        switch ($this->order_type) {
            case 0:
                return $this->getOrderGoodsList($order_id);
                break;
            case 1;
                return $this->getMsOrderGoodsList($order_id);
                break;
            case 2:
                return $this->getPtOrderGoodsList($order_id);
                break;
            case 3:
                break;
            case 4:
                return $this->getInOrderGoodsList($order_id);
                break;
            default:
                return [];
                break;
        }
    }

    //商城订单详情
    private function getOrderGoodsList($order_id)
    {
        $orderDetailList = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select(['od.*', 'g.name', 'g.unit', 'g.attr goods_attr'])->asArray()->all();
        foreach ($orderDetailList as $i => &$item) {
            $item['attr_list'] = \Yii::$app->serializer->decode($item['attr']);
            $item['good_no'] = $this->getGoodNo($item['attr_list'], $item['goods_attr']);
        }
        return $orderDetailList;
    }

    //秒杀订单详情
    private function getMsOrderGoodsList($order_id)
    {
        $order_detail_list = MsOrder::find()->alias('od')
            ->leftJoin(['g' => MsGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                //'od.is_delete' => 0,
                'od.id' => $order_id,
            ])->select('od.*,g.name,g.unit,g.attr goods_attr')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $order_detail_list[$i]['attr_list'] = \Yii::$app->serializer->decode($order_detail['attr']);
            $order_detail_list[$i]['good_no'] = $this->getGoodNo($order_detail_list[$i]['attr_list'], $order_detail['goods_attr']);
        }
        return $order_detail_list;
    }

    /**
     * @param $order_id
     * @return array|\yii\db\ActiveRecord[]
     * 拼团订单详情
     */
    private function getPtOrderGoodsList($order_id)
    {
        $order_detail_list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name,g.attr goods_attr')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $order_detail_list[$i]['attr_list'] = \Yii::$app->serializer->decode($order_detail['attr']);
            $order_detail_list[$i]['good_no'] = $this->getGoodNo($order_detail_list[$i]['attr_list'], $order_detail['goods_attr']);
        }
        return $order_detail_list;
    }

    //商城订单详情
    private function getInOrderGoodsList($order_id)
    {
        $orderDetailList = IntegralOrderDetail::find()->alias('od')
            ->leftJoin(['g' => IntegralGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select(['od.*', 'g.name', 'g.unit', 'g.attr goods_attr'])->asArray()->all();
        foreach ($orderDetailList as $i => &$item) {
            $item['attr_list'] = \Yii::$app->serializer->decode($item['attr']);
            $item['good_no'] = $this->getGoodNo($item['attr_list'], $item['goods_attr']);
        }
        return $orderDetailList;
    }

    public function shareExportData($data, $field)
    {
        $newData = [];
        foreach ($data as $item) {
            $d = [];
            $d['order_no'] = $item['order_no'];
            $d['nickname'] = $item['nickname'];
            $d['price'] = $item['price'];
            $d['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
            $d['bank_name'] = $item['bank_name'];
            $d['name'] = $item['name'];
            $d['pay_type'] = $item['pay_type'];
            $d['platform'] = $item['platform'] ? '支付宝' : '微信';
            $d['bank_card'] = $item['mobile'];
            $d['pay_time'] = isset($item['pay_time']) ? date('Y-m-d H:i:s', $item['pay_time']) : '';
            switch ($item['pay_type']) {
                case 0:
                    $d['pay_type'] = '待打款';
                    break;
                case 1:
                    $d['pay_type'] = '微信自动打款';
                    break;
                case 2:
                    $d['pay_type'] = '手动打款';
                    break;
                default:
                    $d['pay_type'] = '未知';
                    break;
            }
            switch ($item['type']) {
                case 0:
                    $d['type'] = '微信支付';
                    break;
                case 1:
                    $d['type'] = '支付宝支付';
                    break;
                case 2:
                    $d['type'] = '银行卡';
                    break;
                case 3:
                    $d['type'] = '余额';
                    break;
                default:
                    $d['type'] = '未知';
                    break;
            }
            $newData[] = $d;
        }

        $newFields = [];
        foreach ($field as $item) {
            if ($item['selected']) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        Export::order_3($newData, $newFields);
    }

    /**
     * 分销订单导出
     */
    public function shareOrderExportData($sql)
    {
        $handle = fopen('php://temp', 'rwb');
        $newFields = [];
        foreach ($this->fields as $item) {
            if (isset($item['selected']) && $item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }
        $EXCEL_OUT = Export::order_title($newFields);
        fwrite($handle, $EXCEL_OUT);

        $limit = 1000;
        $count = \Yii::$app->db->createCommand("select count(*) " . $sql)->queryScalar();
        $select = "SELECT `al`.*,u.nickname,u.platform ";
        for ($i = 0; $i < $count; $i += $limit) {
            $list = \Yii::$app->db->createCommand($select . $sql . " ORDER BY addtime DESC LIMIT {$limit} OFFSET {$i}")->queryAll();

            $shareOrderForm = new ShareOrderForm();
            $shareOrderForm->store_id = \Yii::$app->controller->store->id;
            $data = $shareOrderForm->transform($list);

            $pay_type_list = ['线上支付', '线上支付', '货到付款', '余额支付'];
            foreach ($this->fields as &$item) {
                if ($this->is_offline == 1) {
                    if (in_array($item['key'], ['clerk_name', 'shop_name'])) {
                        $item['selected'] = 1;
                    }
                } else {
                    if (in_array($item['key'], ['express_price', 'express_no', 'express'])) {
                        $item['selected'] = 1;
                    }
                }
                if (isset($item['selected']) && $item['selected'] == 1) {
                    $newFields[$item['key']] = $item['value'];
                }
            }

            $newList = [];
            foreach ($data as $datum) {
                $newItem = [];
                $newItem['order_no'] = $datum['order_no'];
                $newItem['nickname'] = $datum['nickname'];
                $newItem['platform'] = $datum['platform'] ? '支付宝' : '微信';
                $newItem['total_price'] = $datum['total_price'];
                $newItem['pay_price'] = $datum['pay_price'];
                $newItem['pay_type'] = $pay_type_list[$datum['pay_type']];
                $newItem['is_pay'] = $datum['is_pay'] == 1 ? "已付款" : "未付款";
                $newItem['addtime'] = $datum['addtime'] > 0 ? date('Y-m-d H:i:s', $datum['addtime']) : '';
                $newItem['pay_time'] = $datum['pay_time'] > 0 ? date('Y-m-d H:i:s', $datum['pay_time']) : '';
                $newItem['platform'] = $datum['platform'] ? '支付宝' : '微信';
                $newItem['express_price'] = $datum['express_price'];
                $newItem['name'] = $datum['name'];
                $newItem['mobile'] = $datum['mobile'];
                $newItem['address'] = $datum['address'];
                $newItem['express_no'] = $datum['express_no'];
                $newItem['express'] = $datum['express'];
                $newItem['goods_list'] = $datum['goods_list'];
                $newItem['words'] = $datum['words'];
                $newItem['remark'] = $datum['remark'];

                $newItem['rebate'] = $datum['rebate'];
                $share = isset($datum['share']) ? '昵称:' . $datum['share']['nickname'] . '，姓名:' . $datum['share']['name'] . '，手机号:' . $datum['share']['mobile'] . '，佣金:' . floatval($datum['first_price']) . '|' : '';
                if ($share) {
                    $newItem['share_commission_first'] = $share;
                }
                $share1 = isset($datum['share_1']) ? '昵称:' . $datum['share_1']['nickname'] . '，姓名:' . $datum['share_1']['name'] . '，手机号:' . $datum['share_1']['mobile'] . '，佣金:' . floatval($datum['second_price']) . '|' : '';
                if ($share1) {
                    $newItem['share_commission_second'] = $share1;
                }
                $share2 = isset($datum['share_2']) ? '昵称:' . $datum['share_2']['nickname'] . '，姓名:' . $datum['share_2']['name'] . '，手机号:' . $datum['share_2']['mobile'] . '，佣金:' . floatval($datum['third_price']) . '|' : '';
                if ($share2) {
                    $newItem['share_commission_third'] = $share2;
                }

                $newList[] = $newItem;
            }

            $EXCEL_OUT = Export::order_new($newList, $newFields);
            fwrite($handle, $EXCEL_OUT);
        }

        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');

    }

    /**
     * 用户信息导出
     * @var Query $query;
     */
    public function UserExportData($query)
    {
        $handle = fopen('php://temp', 'rwb');
        $newFields = $this->dataFields();
        $newFields['platform'] = '所属平台';
        $fieldVals = implode(',', array_values($newFields)) . "\n";
        $EXCEL_OUT = mb_convert_encoding($fieldVals, 'GBK', 'UTF-8');

        fwrite($handle, $EXCEL_OUT);

        $limit = 1000;
        $count = $query->count();
        for ($i = 0; $i < $count; $i += $limit) {
            $list = $query->limit($limit)->offset($i)->asArray()->all();
            foreach ($list as $item) {
                $arr = [];
                $arr['id'] = $item['id'];
                $arr['open_id'] = trim("\"\t" . $item['wechat_open_id'] . "\"");
                $arr['nickname'] = $item['nickname'];
                $arr['binding'] = $item['binding'];
                $arr['contact_way'] = $item['contact_way'];
                $arr['comments'] = $item['comments'];
                $arr['addtime'] = trim("\"\t" . date('Y-m-d H:i:s', $item['addtime']) . "\"");

                $clerk = $item['is_clerk'] ? '核销员|' : '';
                $level = isset($item['l_name']) ? $item['l_name'] : '普通会员';
                $arr['identity'] = $clerk . $level;

                $arr['order_count'] = $item['order_count'] ? $item['order_count'] : 0;

                $order = empty($item['orderConsume']) ? 0 : floatval($item['orderConsume']);
                $pt = empty($item['ptOrderConsume']) ? 0 : floatval($item['ptOrderConsume']);
                $yy = empty($item['yyOrderConsume']) ? 0 : floatval($item['yyOrderConsume']);
                $ms = empty($item['msOrderConsume']) ? 0 : floatval($item['msOrderConsume']);
                $integral = empty($item['integralOrderConsume']) ? 0 : floatval($item['integralOrderConsume']);
                $arr['consume_count'] = $order + $pt + $yy + $ms + $integral;
                $arr['coupon_count'] = $item['coupon_count'] ? $item['coupon_count'] : 0;
                $arr['card_count'] = $item['card_count'] ? $item['card_count'] : 0;
                $arr['integral'] = $item['integral'];
                $arr['money'] = $item['money'];
                $arr['platform'] = $item['platform'] ? '支付宝' : '微信';

                $fieldVals = implode(',', array_values($arr)) . "\n";
                $EXCEL_OUT = mb_convert_encoding($fieldVals, 'GBK', 'UTF-8');
                fwrite($handle, $EXCEL_OUT);
            }
        }

        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');

//        Export::order_3($newList, $newFields);
    }

    /**
     * 分销信息导出
     */
    public
    function ShareInfoExportData($list)
    {
        $newFields = $this->dataFields();

        $newList = [];
        foreach ($list as $item) {
            $arr = [];
            $arr['id'] = $item['user_id'];
            $arr['nickname'] = $item['nickname'];
            $arr['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
            $arr['time'] = date('Y-m-d H:i:s', $item['time']);
            $arr['name'] = $item['name'];
            $arr['mobile'] = $item['mobile'];
            $arr['platform'] = $item['platform'] ? '支付宝' : '微信';
            $arr['total_price'] = $item['total_price'];
            $arr['price'] = $item['price'];
            $arr['parent_nickname'] = $item['parent_nickname'] ? $item['parent_nickname'] : '总店';
            $arr['seller_comments'] = $item['seller_comments'];

            switch ($item['status']) {
                case 0:
                    $arr['status'] = '待审核';
                    break;
                case 1:
                    $arr['status'] = '审核通过';
                    break;
                case 2:
                    $arr['status'] = '审核未通过';
                    break;
                default:
                    $arr['status'] = '未知';
                    break;
            }

            $arr['lower_user'] = '一级：' . $item['first'] . '| 二级：' . $item['second'] . '| 三级：' . $item['third'];

            $order = $item['order_count'] ? $item['order_count'] : 0;
            $ms = $item['ms_order_count'] ? $item['ms_order_count'] : 0;
            $pt = $item['pt_order_count'] ? $item['pt_order_count'] : 0;
            $yy = $item['yy_order_count'] ? $item['yy_order_count'] : 0;
            $arr['order'] = '商城订单：' . $order . '| 秒杀订单：' . $ms . '| 拼团订单：' . $pt . '| 预约订单：' . $yy;

            $newList[] = $arr;
        }

        Export::order_3($newList, $newFields);
    }

    /**
     * 分销信息导出
     */
    public
    function MchReportFormsExportData($list)
    {
        $newFields = $this->dataFields();

        $newList = [];
        foreach ($list as $item) {
            $arr = [];
            $arr['id'] = $item['id'];
            $arr['name'] = $item['name'];
            $arr['good_name'] = $item['good_name'];
            $arr['sales_volume'] = $item['sales_volume'];
            $arr['sales_price'] = $item['sales_price'];

            $newList[] = $arr;
        }

        Export::dataNew($newList, $newFields);
    }

    /**
     * 售后订单导出 数据处理
     */
    public
    function refundForm($data)
    {
        $newFields = [];
        foreach ($this->fields as $item) {
            if (isset($item['selected']) && $item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $newList = [];
        foreach ($data as $item) {
            $newItem = $item;
            $newItem['platform'] = $item['platform'] ? '支付宝' : '微信';
            $goods_no = $this->getGoodNo(\Yii::$app->serializer->decode($item['attr']), $item['goods_attr']);
            $newItem['goods_list'] = [
                [
                    'name' => $item['goods_name'],
                    'attr' => $item['attr'],
                    'num' => $item['num'],
                    'good_no' => $goods_no,
                ]
            ];
            switch ($item['refund_type']) {
                case 1:
                    $newItem['refund_type'] = "退货退款";
                    break;
                case 2:
                    $newItem['refund_type'] = "换货";
                    break;
                default:
                    $newItem['refund_type'] = "";
                    break;
            }

            switch ($item['refund_status']) {
                case 0:
                    $newItem['refund_status'] = "待处理";
                    break;
                case 1:
                    $newItem['refund_status'] = "已同意退款退货";
                    break;
                case 2:
                    $newItem['refund_status'] = "已同意换";
                    break;
                case 3:
                    $newItem['refund_status'] = "已拒绝";
                    break;
                default:
                    $newItem['refund_status'] = "";
                    break;
            }
            $newItem['refund_time'] = date('Y-m-d H:i', $item['addtime']);

            $newList[] = $newItem;
        }

        Export::order_3($newList, $newFields);
    }

    private
    function getGoodNo($attr, $goods_attr)
    {
        $good_no = '';
        if (!$goods_attr) {
            return $good_no;
        }
        $ok = false;
        $attr_id_list = [];
        foreach ($attr as $item) {
            $attr_id_list[] = intval($item['attr_id']);
            if (isset($item['no'])) {
                $good_no = $item['no'];
                $ok = true;
                break;
            }
        }
        if (!$ok) {
            sort($attr_id_list);
            $attr_rows = json_decode($goods_attr, true);
            if (empty($attr_rows)) {
                $good_no = '';
            }

            foreach ($attr_rows as $i => $attr_row) {
                $key = [];
                foreach ($attr_row['attr_list'] as $j => $attr) {
                    $key[] = $attr['attr_id'];
                }
                sort($key);
                if (!array_diff($attr_id_list, $key)) {
                    if ($attr_row['no']) {
                        $good_no = $attr_row['no'];
                    }
                }
            }
        }

        return $good_no;
    }

    public
    function dataTransform_new($query)
    {
        $handle = fopen('php://temp', 'rwb');
        $newFields = $this->dataFields();
        $EXCEL_OUT = Export::order_title($newFields);
        fwrite($handle, $EXCEL_OUT);
        $limit = 100;
        $count = $query->count();
        for ($i = 0; $i < $count; $i += $limit) {
            $query->select('o.*');
            if ($this->order_type == 0) {
                $query->with(['user', 'clerk', 'shop', 'orderDetail', 'orderForm']);
            } else if ($this->order_type == 3) {
                $query->with(['user', 'clerk', 'shop', 'goods', 'orderForm']);
            } else {
                $query->with(['user', 'clerk', 'shop', 'orderDetail']);
            }
            $data = $query->limit($limit)->offset($i)->orderBy('o.addtime DESC')->asArray()->all();
            $data = $this->data_new($data);
            $EXCEL_OUT = Export::order_new($data, $newFields);
            fwrite($handle, $EXCEL_OUT);
        }
        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');
    }

// 获取需要导出的字段
    private
    function dataFields()
    {
        $newFields = [];
        foreach ($this->fields as &$item) {
            if ($this->is_offline == 1) {
                if (in_array($item['key'], ['clerk_name', 'shop_name'])) {
                    $item['selected'] = 1;
                }
            } else {
                if (in_array($item['key'], ['express_price', 'express_no', 'express'])) {
                    $item['selected'] = 1;
                }
            }
            if (isset($item['selected']) && $item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }
        return $newFields;
    }

    /**
     * @param $data array 需要处理的数据 临时处理
     */
    private
    function data_new(&$data)
    {
        $pay_type_list = ['线上支付', '线上支付', '货到付款', '余额支付'];
        $newList = [];
        foreach ($data as &$datum) {
            $datum = \Yii::$app->serializer->decode(\Yii::$app->serializer->encode($datum));
            $newItem = [];
            $newItem['order_no'] = $datum->order_no;
            $newItem['nickname'] = $datum->user['nickname'];
            $newItem['platform'] = $datum->user['platform'] ? '支付宝' : '微信';

            $costPriceCount = 0;
            if ($datum->orderDetail) {
                foreach ($datum->orderDetail as $item) {
                    $costPriceCount += isset($item['cost_price']) ? $item['cost_price'] * $item['num'] : 0;
                }
            }

            $newItem['cost_price'] = $costPriceCount;
            $newItem['total_price'] = $datum->total_price;
            $newItem['pay_price'] = $datum->pay_price;
            $newItem['pay_type'] = $pay_type_list[$datum->pay_type];
            $newItem['is_pay'] = $datum['is_pay'] == 1 ? "已付款" : "未付款";
            $newItem['addtime'] = date('Y-m-d H:i', $datum['addtime']);
            $newItem['pay_time'] = $datum->pay_time ? date('Y-m-d H:i', $datum->pay_time) : '';
            $newItem['merchant_remark'] = $datum->seller_comments;

            //是否到店自提 0--否 1--是
            if ((isset($datum['is_offline']) && $datum['is_offline']) || (isset($datum['offline']) && $datum['offline'] == 2) || $this->order_type == 3) {
                $newItem['clerk_name'] = $datum->clerk ? $datum->clerk['nickname'] : '';
                $newItem['shop_name'] = $datum->shop ? $datum->shop['name'] : '';
            } else {
                $newItem['express_price'] = $datum->express_price;
                $newItem['express_no'] = $datum->express_no;
                $newItem['express'] = $datum->express;
            }

            if ($datum->is_cancel) {
                $newItem['order_status'] = '订单已取消';
            } else {
                // TODO 如果卡顿，需去除循环查询
                if ($this->order_type) {
                    $orderRefund = MsOrderRefund::find()->where(['order_id' => $datum->id])->select('status')->one();
                } else {
                    $orderRefund = OrderRefund::find()->where(['order_id' => $datum->id])
                        ->select('status')->one();
                }

                if ($orderRefund) {
                    switch ($orderRefund->status) {
                        case 0:
                            $newItem['order_status'] = '待商家处理';
                            break;
                        case 1:
                            $newItem['order_status'] = '同意并已退款';
                            break;
                        case 2:
                            $newItem['order_status'] = '已同意换货';
                            break;
                        case 3:
                            $newItem['order_status'] = '已拒绝换货';
                            break;
                        default:
                            $newItem['order_status'] = '待处理';
                            break;

                    }
                } else {
                    $newItem['order_status'] = '已完成';
//                    $newItem['order_status'] = $datum['is_confirm'] == 1 ? '已完成' : '进行中';
                }
            }

            if (in_array($this->order_type, [0, 1, 2, 4])) {
                $newItem['name'] = $datum->name;
                $newItem['mobile'] = $datum->mobile;
                $newItem['address'] = $datum->address;
                $newItem['apply_delete'] = ($datum['apply_delete'] == 1) ? "取消中" : "无";
                $newItem['is_send'] = ($datum['is_send'] == 1) ? "已发货" : "未发货";
                $newItem['is_confirm'] = ($datum['is_confirm'] == 1) ? "已收货" : "未收货";
                $newItem['send_time'] = $datum->send_time ? date('Y-m-d H:i', $datum->send_time) : '';
                $newItem['confirm_time'] = $datum->confirm_time ? date('Y-m-d H:i', $datum->confirm_time) : '';
                $newItem['words'] = isset($datum->words) ? $datum->words : '';
                $newItem['goods_list'] = $this->getPtOrderGoodsListNew($datum->orderDetail);
            } else {
                $newItem['goods_list'] = $datum->goods;
            }
            if ($this->order_type == 0 || $this->order_type == 3) {
                if ($datum->orderForm) {
                    $str = '';
                    foreach ($datum->orderForm as $key => $item) {
                        $str .= $item['key'] . ':' . $item['value'] . ',';
                    }
                    $newItem['content'] = rtrim($str, ',');
                } else {
                    $newItem['content'] = $datum->content;
                }
            } else {
                $newItem['content'] = $datum->content;
            }
            if ($this->order_type == 2) {
                $status = ['', '待付款', '拼团中', '拼团成功', '拼团失败'];
                $newItem['status'] = $status[$datum['status']];
                $newItem['content'] = $datum->content;
            }
            if ($this->order_type == 3) {
                $str = '';
                if (is_array($datum->orderForm)) {
                    foreach ($datum->orderForm as $key => $item) {
                        $str .= $item['key'] . ':' . $item['value'] . ',';
                    }
                    $str = rtrim($str, ',');
                }
                $newItem['content'] = $str;
                $newItem['goods_list'] = $datum->goods;
                $newItem['is_use'] = ($datum->is_use == 1) ? '已使用' : '未使用';
                $newItem['use_time'] = date('Y-m-d H:i', $datum->use_time);
            }

            $newList[] = $newItem;
        }

        return $newList;
    }

    /**
     * 拼团订单详情
     */
    private
    function getPtOrderGoodsListNew($list)
    {
        foreach ($list as $i => $order_detail) {
            $list[$i]['attr_list'] = \Yii::$app->serializer->decode($order_detail['attr']);
            $list[$i]['good_no'] = $this->getGoodNo($list[$i]['attr_list'], $order_detail['goods_attr']);
            if ($this->order_type == 1) {
                $list[$i]['total_price'] = $order_detail['pay_price'] - $order_detail['express_price'];
            }
        }
        return $list;
    }
}
