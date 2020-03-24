<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/24
 * Time: 13:53
 */

namespace app\modules\mch\models\integralmall;

use app\models\Express;
use app\models\IntegralGoods;
use app\models\IntegralOrder;
use app\models\IntegralOrderDetail;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\MchModel;
use app\utils\Refund;
use app\utils\TaskCreate;
use yii\data\Pagination;
use app\models\User;
use app\models\Register;

class IntegralOrderForm extends MchModel
{
    public $status;
    public $store_id;
    public $user_id;
    public $limit;
    public $page;
    public $order_id;
    public $express;
    public $express_no;
    public $words;
    public $is_express;
    public $date_start;
    public $date_end;
    public $keyword_1;
    public $keyword;
    public $flag;
    public $fields;
    public $platform;//所属平台


    public function rules()
    {
        return [
            [['status','is_express','keyword_1'], 'integer'],
            [['express', 'express_no', 'words','keyword','date_start', 'date_end'], 'trim'],
            [['express', 'express_no',], 'required', 'on' => 'EXPRESS'],
            [['order_id'], 'required'],
            [['express', 'express_no',], 'string',],
            [['express', 'express_no',], 'default', 'value' => ''],
            [['flag','fields'],'safe']
        ];
    }
    public function search()
    {
        $query = IntegralOrder::find()
            ->where([
                'store_id' => $this->store_id,
            ]);
        if (!isset($this->status)) {
            $this->status = -1;
        }

        if ($this->status == 5) {
            $query->andWhere([
                'is_cancel' => 0,
                'is_delete' => 1,
            ]);
        } else {
            $query->andWhere([
                'is_cancel' => 0,
                'is_delete' => 0,
            ]);
        }

        if($this->status == 8){
            $query->andWhere(['is_recycle' => 1]);
        }else{
            $query->andWhere(['is_recycle' => 0]);
        }

        switch ($this->status) {
            case '0'://未付款
                $query->andWhere([
                    'is_pay' => 0,
                ]);
                break;
            case '1'://待发货
                $query->andWhere([
                    'is_send' => 0,
                ])->andWhere(['or',['is_pay'=>1],['pay_type'=>1]]);
                break;
            case '2'://待收货
                $query->andWhere([
                    'is_send' => 1,
                    'is_confirm' => 0,
                ])->andWhere(['or',['is_pay'=>1],['pay_type'=>1]]);
                break;
            case '3'://已完成
                $query->andWhere([
                    'is_send' => 1,
                    'is_confirm' => 1,
                ])->andWhere(['or',['is_pay'=>1],['pay_type'=>1]]);
                break;
            case '5'://已取消订单
                break;
            case '6'://申请取消待订单
                $query->andWhere(['apply_delete'=>1]);
                break;
            default:
                break;
        }
        if ($this->date_start) {
            $query->andWhere(['>=', 'addtime', strtotime($this->date_start)]);
        }
        if ($this->date_end) {
            $query->andWhere(['<=', 'addtime', strtotime($this->date_end) + 86400]);
        }
        if ($this->keyword) {//关键字查找
            if ($this->keyword_1 == 1) {
                $query->andWhere(['like', 'order_no', $this->keyword]);
            }
            if ($this->keyword_1 == 3) {
                $query->andWhere(['like', 'name', $this->keyword]);
            }
        }

        if ($this->flag == 'EXPORT') {
            $queryExport = clone $query;
            $listEx = $queryExport->alias('o');
            $f = new ExportList();
            $f->order_type = 4;
            $f->fields = $this->fields;
            $f->dataTransform_new($listEx);
        }
        $query->with(['user' => function ($query) {
            $query->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
            ]);
            if (isset($this->platform)) {
                $query->andWhere(['platform' => $this->platform]);
            }
        }])->with(['detail'=>function ($query) {
        }])->with(['shop'=>function ($query) {
            $query->where([
                'is_delete' => 0,
            ]);
        }]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query
            ->orderBy('addtime DESC')
            ->limit($p->limit)
            ->offset($p->offset)
            ->asArray()
            ->all();

        foreach ($list as $k => $item) {
            if (isset($item['address_data'])) {
                $list[$k]['address_data'] = \Yii::$app->serializer->decode($item['address_data']);
            }
        }

        return [$list, $p,$count];
    }

    public function detail()
    {
        $order = IntegralOrder::find()
            ->where([
                'id'=>$this->order_id,
                'store_id' => $this->store_id,
                'is_delete' => 0,
            ])->with(['user' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                ]);
            }])->with(['detail'=>function ($query) {
                $query->where([
                    'is_delete' => 0,
                ]);
            }])->asArray()->one();
        return $order;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = IntegralOrder::findOne([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在或已删除',
            ];
        }
        if ($order->is_pay == 0) {
            return [
                'code' => 1,
                'msg' => '订单未支付'
            ];
        }
        if ($this->is_express == 1) {
            $exportList = Express::getExpressList();
            $ok = false;
            foreach ($exportList as $value) {
                if ($value['name'] == $this->express) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                return [
                    'code'=>1,
                    'msg'=>'快递公司不正确'
                ];
            }
            if (!$this->express) {
                return [
                    'code' => 1,
                    'msg' => '快递公司不能为空'
                ];
            }
            if (!$this->express_no) {
                return [
                    'code' => 1,
                    'msg' => '快递单号不能为空'
                ];
            }
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
                // 创建订单自动收货定时任务
                TaskCreate::orderConfirm($order->id, 'INTEGRAL');
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


    public function getOrderGoodsList($order_id)
    {
        $order_detail_list = IntegralOrderDetail::find()->alias('od')
            ->leftJoin(['g' => IntegralGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name,g.unit,g.cover_pic goods_pic')->asArray()->all();

        foreach ($order_detail_list as $i => &$item) {
            $item['attr_list'] = json_decode($item['attr'], true);
        }
        return $order_detail_list;
    }

    public function agree()
    {
        $order = IntegralOrder::findOne($this->order_id);
        $user = User::findOne($order->user_id);
        $order_detail = IntegralOrderDetail::find()->where(['order_id' => $this->order_id, 'is_delete' => 0])->one();
        $order_detail_attr = json_decode($order_detail->attr);
        foreach ($order_detail_attr as $k => &$v) {
            unset($v->attr_group_id);
            unset($v->attr_group_name);
        }
        $goods = IntegralGoods::findOne($order_detail->goods_id);
        $order->is_delete = 1;
        $order_detail->is_delete = 1;
        if ($order->pay_price > 0) {
            $res = Refund::refund($order, $order->order_no, $order->pay_price);
            if($res !== true){
                return $res;
            }
        }
        $user->integral += $order->integral;
        if ($order->save()) {
            $user->save();  //用户积分恢复
            $register = new Register();
            $register->store_id = $this->store_id;
            $register->user_id = $user->id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 12;
            $register->integral = $order->integral;
            $register->order_id = $order->id;
            $register->save();

            //商品库存恢复
            $attr_id_list = [];
            foreach ($order_detail_attr as $value) {
                $attr_id_list[] = $value['attr_id'];
            }
            if ($goods->numAdd($attr_id_list, 1)) {
                //删除订单详情
                if ($order_detail->save()) {
                    $msg_sender = new WechatTplMsgSender($this->store_id, $order->id, $this->getWechat());
                    if ($order->is_pay) {
                        $remark = '订单已取消，退款金额：' . $order->pay_price;
                        $msg_sender->revokeMsg($remark);
                    } else {
                        $msg_sender->revokeMsg();
                    }
                    return [
                        'code' => 0,
                        'msg' => '订单已取消'
                    ];
                }
            }
        }
        if (!$order->save()) {
            return $this->getErrorResponse($order);
        }
        if (!$order_detail->save()) {
            return $this->getErrorResponse($order_detail);
        }
    }
}
