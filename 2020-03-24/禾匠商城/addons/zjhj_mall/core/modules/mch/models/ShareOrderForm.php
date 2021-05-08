<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3
 * Time: 9:20
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\OrderShare;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Share;
use app\models\Shop;
use app\models\User;
use app\models\YyGoods;
use app\models\YyOrder;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\VarDumper;

class ShareOrderForm extends MchModel
{

    public $store_id;
    public $user_id;
    public $keyword;
    public $status;
    public $page;
    public $limit;

    public $flag;//是否导出
    public $fields;
    public $is_offline;
    public $clerk_id;
    public $parent_id;
    public $shop_id;
    public $order_type;
    public $platform;//所属平台
    public $date_start;
    public $date_end;

    public function rules()
    {
        return [
            [['keyword', 'order_type', 'flag', 'date_start', 'date_end'], 'trim'],
            [['status', 'page', 'limit', 'user_id', 'is_offline', 'clerk_id', 'shop_id'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            //[['limit',], 'default', 'value' => 20],
            [['flag'], 'default', 'value' => 'no'],
            [['order_type'], 'string'],
            [['order_type'], 'in', 'range' => ['s', 'ms', 'pt', 'yy','ds','b']],
            [['fields'], 'safe'],

        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $s_table = Order::tableName();
        $ms_table = MsOrder::tableName();
        $pt_table = PtOrder::tableName();
        $yy_table = YyOrder::tableName();
        $order_share_table = OrderShare::tableName();
        $user_table = User::tableName();
        $sql_s = "
            SELECT
                's' AS `order_type`,
                `id`,
                `order_no`,
                `is_pay`,
                `user_id`,
                `apply_delete`,
                `addtime`,
                `parent_id` AS `parent_id_1`,
                `parent_id_1` AS `parent_id_2`,
                `parent_id_2` AS `parent_id_3`,
                `first_price`,
                `second_price`,
                `third_price`,
                `rebate`
            FROM
                {$s_table}
            WHERE
                (`store_id` = {$this->store_id})
            AND (`is_cancel` = 0)
            AND (`is_delete` = 0)
            AND (`is_recycle` = 0)
            AND (`is_show` = 1)
            AND (`parent_id` > 0 OR `rebate` != 0)
            AND (mch_id = 0 OR (mch_id > 0 AND `version` >= '2.7.2'))
        ";
        $sql_ms = "(
            SELECT
                'ms' AS `order_type`,
                `id`,
                `order_no`,
                `is_pay`,
                `user_id`,
                `apply_delete`,
                `addtime`,
                `parent_id` AS `parent_id_1`,
                `parent_id_1` AS `parent_id_2`,
                `parent_id_2` AS `parent_id_3`,
                `first_price`,
                `second_price`,
                `third_price`,
                `rebate`
            FROM
                {$ms_table}
            WHERE
                (`store_id` = {$this->store_id})
            AND (`is_delete` = 0)
            AND (`is_sum` = 1)
            AND (`is_cancel` = 0)
            AND (`is_recycle` = 0)
            AND (`is_show` = 1)
            AND (`parent_id` > 0 OR `rebate` != 0)
        )";
        $sql_pt = "(
            SELECT
                'pt' AS `order_type`,
                `pt`.`id`,
                `pt`.`order_no`,
                `pt`.`is_pay`,
                `pt`.`user_id`,
                `pt`.`apply_delete`,
                `pt`.addtime,
                `os`.`parent_id_1`,
                `os`.`parent_id_2`,
                `os`.`parent_id_3`,
                `os`.`first_price`,
                `os`.`second_price`,
                `os`.`third_price`,
                `os`.`rebate`
            FROM
                {$pt_table} `pt`
            LEFT JOIN {$order_share_table} `os` ON pt.id = os.order_id AND `os`.`type` = 0
            WHERE
                (`pt`.`store_id` = {$this->store_id})
            AND (`pt`.`is_delete` = 0)
            AND (`pt`.`is_cancel` = 0)
            AND (`pt`.`is_recycle` = 0)
            AND (`pt`.`is_show` = 1)
            AND (`os`.`parent_id_1` > 0 OR `os`.`rebate` != 0)
        )";
        $sql_yy = "(
            SELECT
                'yy' AS `order_type`,
                `yy`.`id`,
                `yy`.`order_no`,
                `yy`.`is_pay`,
                `yy`.`user_id`,
                `yy`.`apply_delete`,
                `yy`.addtime,
                `os`.`parent_id_1`,
                `os`.`parent_id_2`,
                `os`.`parent_id_3`,
                `os`.`first_price`,
                `os`.`second_price`,
                `os`.`third_price`,
                `os`.`rebate`
            FROM
                {$yy_table} `yy`
            LEFT JOIN {$order_share_table} `os` ON os.order_id = yy.id AND `os`.`type` = 1
            WHERE
                (`yy`.`store_id` = {$this->store_id})
            AND (`yy`.`is_delete` = 0)
            AND (`yy`.`is_cancel` = 0)
            AND (`yy`.`is_recycle` = 0)
            AND (`yy`.`is_show` = 1)
            AND (`os`.`parent_id_1` > 0 OR `os`.`rebate` != 0)
        )";
        $select = "SELECT `al`.*,u.nickname,u.platform ";

        if($this->order_type){
            switch($this->order_type){
                case 's':
                    $sqlTable = '('.$sql_s . 'AND mch_id = 0)';
                    break;
                case 'ds':
                    $sqlTable = '('.$sql_s . 'AND mch_id > 0)';
                    break;
                case 'ms':
                    $sqlTable = $sql_ms;
                    break;
                case 'pt':
                    $sqlTable = $sql_pt;
                    break;
                case 'yy':
                    $sqlTable = $sql_yy;
                    break;
                case 'b':
                    $sqlTable = '('.$sql_s . 'AND type = 2)';
                    break;
                default:
                    $sqlTable = $sql_s;
            }
            $sql = "FROM {$sqlTable} `al` LEFT JOIN {$user_table} `u` ON al.user_id = u.id";
        }else{
            $sql = "FROM ( ({$sql_s}) UNION {$sql_ms} UNION {$sql_pt} UNION {$sql_yy} ) `al` LEFT JOIN {$user_table} `u` ON al.user_id = u.id";
        }

        $andWhere = '';
        if ($this->keyword) {
            if (!empty($andWhere)) {
                $andWhere .= " AND ";
            } else {
                $andWhere = " WHERE ";
            }
            $andWhere .= " (`al`.order_no LIKE '%{$this->keyword}%' OR u.nickname LIKE '%{$this->keyword}%') ";
        }

        if (isset($this->date_start) && $this->date_start) {
            if (!empty($andWhere)) {
                $andWhere .= " AND ";
            } else {
                $andWhere = " WHERE ";
            }
            $andWhere .= "`al`.`addtime` >=" . strtotime($this->date_start);
        }
        if (isset($this->date_end) && $this->date_end) {
            if (!empty($andWhere)) {
                $andWhere .= " AND ";
            } else {
                $andWhere = " WHERE ";
            }
            $andWhere .= "`al`.`addtime` <=" . strtotime($this->date_end);
        }

        if ($this->parent_id) {
            if (!empty($andWhere)) {
                $andWhere .= " AND ";
            } else {
                $andWhere = " WHERE ";
            }
            $andWhere .= " (`al`.parent_id_1 = {$this->parent_id}) ";
        }

        if (isset($this->platform)) {
            if (!empty($andWhere)) {
                $andWhere .= " AND ";
            } else {
                $andWhere = " WHERE ";
            }
            $andWhere .= "(`u`.platform = {$this->platform})";
        }

        $sql .= $andWhere;

        if ($this->flag == "EXPORT") {
            $export = new ExportList();
            $export->is_offline = $this->is_offline;
            $export->order_type = 0;
            $export->fields = $this->fields;
            $export->shareOrderExportData($sql);
        }

        $count = \Yii::$app->db->createCommand("select count(*) " . $sql)->queryScalar();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = \Yii::$app->db->createCommand($select . $sql . " ORDER BY addtime DESC LIMIT {$pagination->limit} OFFSET {$pagination->offset}")->queryAll();
        $list = $this->transform($list);

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }

    public function transform($list) {

        foreach ($list as $i => $item) {
            if ($item['order_type'] == 's') {
                $list[$i]['goods_list'] = $this->getOrderGoodsList($item['id']);
                $order = Order::find()->where(['id' => $item['id']])->asArray()->one();
                $order_refund = OrderRefund::findOne(['store_id' => $this->store_id, 'order_id' => $item['id'], 'is_delete' => 0]);
                $list[$i]['refund'] = "";
                if ($order_refund) {
                    $list[$i]['refund'] = $order_refund->status;
                }
                if($order['mch_id'] > 0){
                    $list[$i]['order_type'] = 'ds';
                }
            }
            if ($item['order_type'] == 'ms') {
                $list_ms = $this->getMsOrderGoodsList($item['id']);
                $list[$i]['goods_list'] = $list_ms;
                $order = MsOrder::find()->where(['id' => $item['id']])->asArray()->one();
            }
            if ($item['order_type'] == 'pt') {
                $list_pt = $this->getPtOrderGoodsList($item['id']);
                $list[$i]['goods_list'] = $list_pt;
                $order = PtOrder::find()->where(['id' => $item['id']])->asArray()->one();
            }
            if ($item['order_type'] == 'yy') {
                $list_yy = $this->geYytOrderGoodsList($item['id']);
                $list[$i]['goods_list'] = $list_yy;
                $order = $list_yy[0];
            }
            $list[$i] = array_merge($list[$i], $order);
            if ($order['is_offline'] == 1 || $item['order_type'] == 'yy') {
                $user = User::findOne(['id' => $order['clerk_id'], 'store_id' => $this->store_id]);
                $list[$i]['clerk_name'] = $user->nickname;
            }
            if ($order['shop_id'] && $order['shop_id'] != 0) {
                $shop = Shop::find()->where(['store_id' => $this->store_id, 'id' => $order['shop_id']])->asArray()->one();
                $list[$i]['shop'] = $shop;
            }
            if ($item['parent_id_1'] != 0 && $item['parent_id_1'] != -1) {
                $share = User::find()->alias('u')->where(['u.id' => $item['parent_id_1'], 'u.store_id' => $this->store_id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $list[$i]['share'] = $share;
            }
            if ($item['parent_id_2'] != 0 && $item['parent_id_1'] != -1) {
                $share_1 = User::find()->alias('u')->where(['u.id' => $item['parent_id_2'], 'u.store_id' => $this->store_id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $list[$i]['share_1'] = $share_1;
            }
            if ($item['parent_id_3'] != 0 && $item['parent_id_1'] != -1) {
                $share_2 = User::find()->alias('u')->where(['u.id' => $item['parent_id_3'], 'u.store_id' => $this->store_id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $list[$i]['share_2'] = $share_2;
            }
        }

        return $list;
    }

    public function getOrderGoodsList($order_id)
    {
        $order_detail_list = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name,g.unit')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $order_detail_list[$i]['attr_list'] = json_decode($order_detail['attr']);
        }
        return $order_detail_list;
    }

    public function getMsOrderGoodsList($order_id)
    {
        $list = MsOrder::find()->alias('o')->where(['o.id' => $order_id])
        ->leftJoin(['g' => MsGoods::tableName()],'g.id = o.goods_id')
            ->select(['o.attr', 'g.name', 'o.total_price', 'o.num','g.cover_pic pic'])
            ->asArray()->one();
        $list['attr_list'] = json_decode($list['attr']);
        return [$list];
    }

    public function getPtOrderGoodsList($order_id)
    {
        $list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id = g.id')
            ->where(['od.order_id' => $order_id, 'od.is_delete' => 0])
            ->select(['od.*', 'g.name', 'g.unit'])->asArray()->one();
        $list['attr_list'] = json_decode($list['attr']);
        return [$list];
    }

    public function geYytOrderGoodsList($order_id)
    {
        $list = YyOrder::find()->alias('o')
            ->leftJoin(['g' => YyGoods::tableName()], 'g.id = o.goods_id')
            ->where(['o.id' => $order_id])
            ->select(['o.*', 'g.name', 'g.cover_pic pic'])->asArray()->one();
        return [$list];
    }
}
