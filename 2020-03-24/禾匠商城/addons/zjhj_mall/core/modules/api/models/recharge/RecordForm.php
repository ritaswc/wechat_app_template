<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16
 * Time: 15:00
 */

namespace app\modules\api\models\recharge;


use app\hejiang\ApiResponse;
use app\models\Goods;
use app\models\IntegralLog;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\Pond;
use app\models\PondLog;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\models\Recharge;
use app\models\ReOrder;
use app\models\Scratch;
use app\models\ScratchLog;
use app\modules\api\models\ApiModel;
use app\models\YyOrder;
use app\models\YyGoods;

class RecordForm extends ApiModel
{
    public $store_id;
    public $user;

    public $date;


    public function rules()
    {
        return [
            [['date'], 'date','format' => 'YYYY-mm'],
            [['date'], 'default', 'value' => function () {
                return date('Y-m', time());
            }],
            [['store_id'],'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        //搜索置顶月份的充值记录及余额消费记录
        $date = $this->date;
        $start = strtotime($date);
        $end = strtotime(date('Y-m-t', $start)) + 86400;
        $sql = $this->getSql();
        $select = "SELECT * ";
        $where = " WHERE al.addtime >= {$start} AND al.addtime <= {$end}";
        $list = \Yii::$app->db->createCommand($select . $sql . $where . " ORDER BY al.addtime DESC")->queryAll();
        foreach ($list as $index => $value) {
            if (in_array($value['order_type'], ['log'])) {
                if(strpos($value['content'],'充值') === false){
                    $list[$index]['flag'] = 1;
                    $list[$index]['price'] = '-' . (floatval($value['pay_price']) + floatval($value['send_price']));
                    $list[$index]['content'] = "扣除";
                }else{
                    $list[$index]['flag'] = 0;
                    $list[$index]['price'] = '+' . (floatval($value['pay_price']) + floatval($value['send_price']));
                    $list[$index]['content'] = "充值";
                }
            } elseif (in_array($value['order_type'], ['r'])) {
                $list[$index]['flag'] = 0;
                $list[$index]['price'] = '+' . (floatval($value['pay_price']) + floatval($value['send_price']));
                $list[$index]['content'] = "充值";
            } elseif (in_array($value['order_type'], ['s_re', 'ms_re', 'pt_re','yy_re'])) {
                $list[$index]['flag'] = 2;
                $list[$index]['price'] = '+' . (floatval($value['pay_price']) + floatval($value['send_price']));
                $list[$index]['content'] = "订单退款";
            } elseif(in_array($value['order_type'], ['pond'])){
                $list[$index]['flag'] = 0;
                $list[$index]['price'] = '+' . (floatval($value['pay_price']) + floatval($value['send_price']));
                $list[$index]['content'] = "九宫格抽奖获得";
            } elseif(in_array($value['order_type'], ['scratch'])){
                $list[$index]['flag'] = 0;
                $list[$index]['price'] = '+' . (floatval($value['pay_price']) + floatval($value['send_price']));
                $list[$index]['content'] = "刮刮卡获得";
            }
            else{
                $list[$index]['flag'] = 1;
                $list[$index]['price'] = '-' . floatval($value['pay_price']);
                if($value['order_type'] == 's'){
                    $goods = Goods::find()->alias('g')->where([
                        'g.store_id'=>$this->store_id
                    ])->leftJoin(['od'=>OrderDetail::tableName()],'od.goods_id=g.id')
                        ->andWhere(['od.order_id'=>$value['id']])->select(['g.name'])->asArray()->column();
                    $goods_str = implode(',', $goods);
                    $list[$index]['content'] = "消费-商城订单{$goods_str}";
                }elseif($value['order_type'] == 'ms'){
                    $goods = MsGoods::find()->alias('g')->where([
                        'g.store_id'=>$this->store_id
                    ])->leftJoin(['o'=>MsOrder::tableName()],'o.goods_id=g.id')
                        ->andWhere(['o.id'=>$value['id'],'o.is_pay'=>1,'o.pay_type'=>3])->select(['g.name'])->asArray()->one();
                    $list[$index]['content'] = "消费-秒杀订单{$goods['name']}";
                }elseif($value['order_type'] == 'pt'){
                    $goods = PtGoods::find()->alias('g')->where(['g.store_id'=>$this->store_id])
                        ->leftJoin(['od'=>PtOrderDetail::tableName()],'od.goods_id=g.id')
                        ->andWhere(['od.order_id'=>$value['id']])->select(['g.name'])->asArray()->one();
                    $list[$index]['content'] = "消费-拼团订单{$goods['name']}";
                }elseif($value['order_type'] == 'yy'){
                    //todo
                    $order = YyOrder::findOne(['id'=>$value['id']]);
                    $goods = YyGoods::find()->alias('g')->where(['g.store_id'=>$this->store_id])
                        ->andWhere(['id' => $order->goods_id])
                        ->select(['g.name'])->asArray()->one();
                    $list[$index]['content'] = "消费-预约订单{$goods['name']}";
                }else{
                    $list[$index]['content'] = "消费-订单{$value['order_no']}";
                }
            }
            $list[$index]['date'] = date('Y-m-d H:i:s', $value['addtime']);
        }
        return new ApiResponse(0,'success',[
            'list' => $list,
            'date' => $date
        ]);

    }

    public function getSql()
    {
        $r_table = ReOrder::tableName();
        $s_table = Order::tableName();
        $ms_table = MsOrder::tableName();
        $pt_table = PtOrder::tableName();
        $yy_table = YyOrder::tableName();

        $s_refund_table = OrderRefund::tableName();
        $ms_refund_table = MsOrderRefund::tableName();
        $pt_refund_table = PtOrderRefund::tableName();
        $yy_refund_table = YyOrder::tableName();

        $pondTable = PondLog::tableName();
        $scratchTable = ScratchLog::tableName();

        $logTable = IntegralLog::tableName();
        $query_r = "(
            SELECT
            'r' AS order_type,
            id,
            addtime,
            pay_price,
            send_price,
            '' AS content
            FROM {$r_table}
            WHERE store_id = {$this->store_id}
            AND user_id = {$this->user->id}
            AND is_delete = 0
            AND is_pay = 1
        )";
        $query_s = "(
            SELECT
            's' AS order_type,
            id,
            addtime,
            pay_price,
            0 AS send_price,
            '' AS content
            FROM {$s_table}
            WHERE store_id = {$this->store_id}
            AND user_id = {$this->user->id}
            AND is_delete = 0
            AND is_cancel = 0
            AND is_pay = 1
            AND pay_type = 3
            AND is_show = 1
        )";
        $query_ms = "(
            SELECT
            'ms' AS order_type,
            id,
            addtime,
            pay_price,
            0 AS send_price,
            '' AS content
            FROM {$ms_table}
            WHERE store_id = {$this->store_id}
            AND user_id = {$this->user->id}
            AND is_delete = 0
            AND is_cancel = 0
            AND is_pay = 1
            AND pay_type = 3
            AND is_show = 1
        )";
        $query_pt = "(
            SELECT
            'pt' AS order_type,
            id,
            addtime,
            pay_price,
            0 AS send_price,
            '' AS content
            FROM {$pt_table}
            WHERE store_id = {$this->store_id}
            AND user_id = {$this->user->id}
            AND is_delete = 0
            AND is_cancel = 0
            AND is_pay = 1
            AND pay_type = 3
            AND is_show = 1
        )";

        $query_yy = "(
            SELECT
            'yy' AS order_type,
            id,
            addtime,
            pay_price,
            0 AS send_price,
            '' AS content
            FROM {$yy_table}
            WHERE store_id = {$this->store_id}
            AND user_id = {$this->user->id}
            AND is_delete = 0
            AND is_cancel = 0
            AND is_pay = 1
            AND pay_type = 2
            AND is_recycle = 0
            AND is_show = 1
        )";
        $query_s_re = "(
            SELECT
            's_re' AS order_type,
            ore.id,
            ore.addtime,
            ore.refund_price AS pay_price,
            0 AS send_price,
            '' AS content
            FROM {$s_refund_table} AS ore
            LEFT JOIN {$s_table} AS o ON o.id = ore.order_id
            WHERE ore.store_id = {$this->store_id}
            AND ore.is_delete = 0
            AND ore.type = 1
            AND ore.status = 1
            AND o.pay_type = 3
            AND ore.user_id = {$this->user->id}
            AND o.is_show = 1
        )";

        $query_ms_re = "(
            SELECT
            'ms_re' AS order_type,
            ore.id,
            ore.addtime,
            ore.refund_price AS pay_price,
            0 AS send_price,
            '' AS content
            FROM {$ms_refund_table} AS ore
            LEFT JOIN {$ms_table} AS o ON o.id = ore.order_id
            WHERE ore.store_id = {$this->store_id}
            AND ore.is_delete = 0
            AND ore.type = 1
            AND ore.status = 1
            AND o.pay_type = 3
            AND ore.user_id = {$this->user->id}
            AND o.is_show = 1
        )";

        $query_pt_re = "(
            SELECT
            'pt_re' AS order_type,
            ore.id,
            ore.addtime,
            ore.refund_price AS pay_price,
            0 AS send_price,
            '' AS content
            FROM {$pt_refund_table} AS ore
            LEFT JOIN {$pt_table} AS o ON o.id = ore.order_id
            WHERE ore.store_id = {$this->store_id}
            AND ore.is_delete = 0
            AND ore.type = 1
            AND ore.status = 1
            AND o.pay_type = 3
            AND ore.user_id = {$this->user->id}
            AND o.is_show = 1
        )";

        $query_yy_re = "(
            SELECT
            'yy_re' AS order_type,
            ore.id,
            ore.refund_time AS addtime,
            ore.pay_price,
            0 AS send_price,
            '' AS content
            FROM {$yy_refund_table} AS ore
            WHERE ore.store_id = {$this->store_id}
            AND ore.user_id = {$this->user->id}
            AND ore.is_pay = 1
            AND ore.is_delete = 0
            AND ore.pay_type = 2
            AND ore.is_cancel = 0
            AND ore.is_refund = 1
            AND ore.is_recycle = 0
            AND ore.is_show = 1
        )";

        $query_log = "(
            SELECT
            'log' AS order_type,
            lt.id,
            lt.addtime,
            lt.integral AS pay_price,
            0 AS send_price,
            lt.content
            FROM {$logTable} AS lt
            WHERE lt.store_id = {$this->store_id}
            AND lt.type = 1
            AND lt.user_id = {$this->user->id}
        )";

        $pondLog = "(
            SELECT
            'pond' AS order_type,
            p.id,
            p.create_time as addtime,
            p.price AS pay_price,
            0 AS send_price,
            '' AS content
            FROM {$pondTable} AS p
            WHERE p.store_id = {$this->store_id}
            AND p.type = 1
            AND p.status = 1
            AND p.user_id = {$this->user->id}
        )";

        $scratchLog = "(
            SELECT
            'scratch' AS order_type,
            id,
            create_time as addtime,
            price AS pay_price,
            0 AS send_price,
            '' AS content
            FROM {$scratchTable}
            WHERE store_id = {$this->store_id}
            AND type = 1
            AND status = 2
            AND user_id = {$this->user->id}
        )";

        $sql = " FROM (
            {$query_r}
            UNION {$query_s}
            UNION {$query_ms}
            UNION {$query_pt}
            UNION {$query_yy}
            UNION {$query_s_re}
            UNION {$query_ms_re}
            UNION {$query_pt_re}
            UNION {$query_yy_re}
            UNION {$query_log}
            UNION {$pondLog}
            UNION {$scratchLog}
        ) AS al ";
        return $sql;
    }
}