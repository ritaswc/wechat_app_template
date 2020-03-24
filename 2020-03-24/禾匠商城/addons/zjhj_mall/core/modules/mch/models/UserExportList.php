<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models;


use app\modules\mch\extensions\Export;

class UserExportList
{
    public $fields;


    public function getList($type)
    {
        // $type 1.余额充值|2.会员购买|3.积分充值
        $list = [
            [
                'key' => 'order_no',
                'value' => '订单号/说明',
                'type' => [1]
            ],
            [
                'key' => 'order_no',
                'value' => '订单号',
                'type' => [2]
            ],
            [
                'key' => 'nickname',
                'value' => '昵称',
                'type' => [1, 2]
            ],
            [
                'key' => 'username',
                'value' => '昵称',
                'type' => [3]
            ],
            [
                'key' => 'pay_price',
                'value' => '支付金额',
                'type' => [1, 2]
            ],
            [
                'key' => 'pay_time',
                'value' => '支付日期',
                'type' => [1, 2]
            ],
            [
                'key' => 'send_price',
                'value' => '赠送金额',
                'type' => [1]
            ],
            [
                'key' => 'explain',
                'value' => '备注',
                'type' => [1]
            ],
            [
                'key' => 'after_name',
                'value' => '购买后',
                'type' => [2]
            ],
            [
                'key' => 'current_name',
                'value' => '购买前',
                'type' => [2]
            ],
            [
                'key' => 'content',
                'value' => '充值说明',
                'type' => [3]
            ],
            [
                'key' => 'operator',
                'value' => '操作者',
                'type' => [3]
            ],
            [
                'key' => 'integral',
                'value' => '充值积分',
                'type' => [3]
            ],
            [
                'key' => 'addtime',
                'value' => '充值时间',
                'type' => [3]
            ]
        ];

        $newArr = [];
        foreach ($list as $item) {
            if (in_array($type, $item['type'])) {
                $newArr[] = $item;
            }
        }

        return $newArr;
    }

    /**
     * 余额充值记录导出
     */
    public function rechargeForm($query)
    {
        //筛选已选择的字段,并重新组合数据结构
        $newFields = [];
        foreach ($this->fields as $item) {
            if ($item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $handle = fopen('php://temp', 'rwb');
        $EXCEL_OUT = Export::order_title($newFields);
        fwrite($handle, $EXCEL_OUT);
        $limit = 100;
        $count = $query->count();

        for ($i = 0; $i < $count; $i += $limit) {
            $data = $query->limit($limit)->offset($i)->orderBy(['qt.pay_time' => SORT_DESC])->all();
            $newData = [];
            foreach ($data as $item) {
                $temporaryData = [];
                $temporaryData['nickname'] = $item['nickname'];
                $temporaryData['platform'] = $item['platform'] ? '支付宝' : '微信';
                $temporaryData['pay_price'] = $item['pay_price'];
                $temporaryData['pay_time'] = date('Y-m-d H:i:s', $item['pay_time']);
                $temporaryData['order_no'] = $item['order_no'];
                $temporaryData['send_price'] = $item['send_price'];
                $temporaryData['explain'] = $item['explain'];

                $newData[] = $temporaryData;
            }
            $EXCEL_OUT = Export::order_new($newData, $newFields);
            fwrite($handle, $EXCEL_OUT);
        }
        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');
    }

    /**
     * 会员购买记录导出
     */
    public function memberBuyForm($query)
    {
        //筛选已选择的字段,并重新组合数据结构
        $newFields = [];
        foreach ($this->fields as $item) {
            if ($item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $handle = fopen('php://temp', 'rwb');
        $EXCEL_OUT = Export::order_title($newFields);
        fwrite($handle, $EXCEL_OUT);
        $limit = 100;
        $count = $query->count();
        for ($i = 0; $i < $count; $i += $limit) {
            $data = $query->limit($limit)->offset($i)->orderBy('ro.addtime DESC')->asArray()->all();
            $newData = [];
            foreach ($data as $item) {
                $temporaryData = [];
                $temporaryData['order_no'] = $item['order_no'];
                $temporaryData['pay_price'] = $item['pay_price'];
                $temporaryData['pay_time'] = date('Y-m-d H:i:s', $item['pay_time']);
                $temporaryData['nickname'] = $item['nickname'];
                $temporaryData['platform'] = $item['platform'] ? '支付宝' : '微信';
                $temporaryData['after_name'] = $item['after_name'];
                $temporaryData['current_name'] = $item['current_name'];

                $newData[] = $temporaryData;
            }
            $EXCEL_OUT = Export::order_new($newData, $newFields);
            fwrite($handle, $EXCEL_OUT);
        }
        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');
    }

    /**
     * 积分充值记录导出
     */
    public function integralForm($query)
    {
        //筛选已选择的字段,并重新组合数据结构
        $newFields = [];
        foreach ($this->fields as $item) {
            if ($item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $handle = fopen('php://temp', 'rwb');
        $EXCEL_OUT = Export::order_title($newFields);
        fwrite($handle, $EXCEL_OUT);
        $limit = 100;
        $count = $query->count();
        for ($i = 0; $i < $count; $i += $limit) {
            $data = $query->orderBy('addtime DESC')
                ->limit($limit)
                ->offset($i)
                ->all();
            $newData = [];
            foreach ($data as $item) {
                $temporaryData = [];
                $temporaryData['content'] = $item['content'];
                $temporaryData['integral'] = $item['integral'];
                $temporaryData['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
                $temporaryData['operator'] = $item['operator'];
                $temporaryData['username'] = $item['username'];
                $temporaryData['platform'] = $item['user']['platform'] ? '支付宝' : '微信';

                $newData[] = $temporaryData;
            }
            $EXCEL_OUT = Export::order_new($newData, $newFields);
            fwrite($handle, $EXCEL_OUT);
        }
        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, $name . '.csv');
    }
}