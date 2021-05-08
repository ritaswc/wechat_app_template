<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/13
 * Time: 11:27
 */

namespace app\plugins\bargain\models;


use app\models\Model;

/**
 * @property \app\models\Goods $goods
 * @property \app\models\BargainGoods $bargain
 */
class GoodsForm extends Model
{
    public $goods;
    public $bargain;

    public $people;
    public $human;
    public $min_price;
    public $begin_time;
    public $end_time;
    public $time;
    public $status;
    public $first_min_money;
    public $first_max_money;
    public $second_min_money;
    public $second_max_money;

    public function rules()
    {
        return [
            [['people', 'human', 'status', 'min_price', 'begin_time', 'end_time', 'time', 'first_min_money', 'first_max_money', 'second_min_money', 'second_max_money'], 'trim'],
            [['human', 'min_price', 'begin_time', 'end_time', 'time', 'first_min_money', 'first_max_money', 'second_min_money', 'second_max_money'], 'required'],
            [['people', 'human', 'status', 'time'], 'integer', 'min' => 0],
            [['min_price','first_min_money', 'first_max_money', 'second_min_money', 'second_max_money',], 'number', 'min' => 0, 'max' => 99999999],
            [['people', 'status'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'people' => '参与人数',
            'human' => '人数',
            'min_price' => '最低价',
            'begin_time' => '开始时间',
            'end_time' => '结束时间',
            'time' => '砍价时间',
            'status' => '砍价方式',
            'first_min_money' => '价格波动值',
            'first_max_money' => '价格波动值',
            'second_max_money' => '价格波动值',
            'second_min_money' => '价格波动值',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if ($this->bargain->isNewRecord) {
            $this->bargain->is_delete = 0;
            $this->bargain->addtime = time();
            $this->bargain->goods_id = $this->goods->id;
            $this->bargain->store_id = $this->goods->store_id;
        }
        if ($this->goods->price <= $this->min_price) {
            return [
                'code' => 1,
                'msg' => '砍价最低价必须小于商品售价'
            ];
        }
        $this->bargain->min_price = $this->min_price;
        $this->bargain->begin_time = strtotime($this->begin_time);
        $this->bargain->end_time = strtotime($this->end_time);
        if($this->bargain->end_time < strtotime(date('Y-m-d H:i',time()))){
            return [
                'code'=>1,
                'msg'=>'结束时间不能小于今天'
            ];
        }
        if($this->bargain->begin_time > $this->bargain->end_time){
            return [
                'code'=>1,
                'msg'=>'开始时间不能大于结束时间'
            ];
        }
        $this->bargain->time = $this->time;
        $this->bargain->status = $this->status;
        if ($this->people > 0 && $this->people < 2) {
            return [
                'code' => 1,
                'msg' => '若填写参与人数，参与人数必须大于1'
            ];
        }
        $status_data = [
            'people' => $this->people,
            'human' => $this->human,
            'first_min_money' => $this->first_min_money,
            'first_max_money' => $this->first_max_money,
            'second_min_money' => $this->second_min_money,
            'second_max_money' => $this->second_max_money
        ];
        $this->bargain->status_data = \Yii::$app->serializer->encode($status_data);
        if ($this->bargain->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->bargain);
        }


    }

    public function search()
    {
        $bargain = $this->bargain;
        $newList = [];
        if ($bargain) {
            $newList['min_price'] = $bargain->min_price;
            $newList['begin_time'] = $bargain->beginTimeText;
            $newList['end_time'] = $bargain->endTimeText;
            $newList['time'] = $bargain->time;
            $newList['status'] = $bargain->status;
            $statusData = $bargain->status_data ? \Yii::$app->serializer->decode($bargain->status_data) : '';
            $newList['people'] = $statusData->people;
            $newList['human'] = $statusData->human;
            $newList['first_min_money'] = $statusData->first_min_money;
            $newList['first_max_money'] = $statusData->first_max_money;
            $newList['second_min_money'] = $statusData->second_min_money;
            $newList['second_max_money'] = $statusData->second_max_money;
        }
        return $newList;
    }

}