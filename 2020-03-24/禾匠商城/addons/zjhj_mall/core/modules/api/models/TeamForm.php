<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/14
 * Time: 16:24
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\GoodsPic;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\OrderShare;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\models\Setting;
use app\models\Share;
use app\models\User;
use app\models\UserShareMoney;
use app\models\YyGoods;
use app\models\YyOrder;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\VarDumper;

class TeamForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $share_setting;

    public $status;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit', 'status',], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 10],
            [['status'], 'in', 'range' => [1, 2, 3], 'on' => 'TEAM'],
            [['status'], 'in', 'range' => [-1, 0, 1, 2], 'on' => 'ORDER'],
            [['store_id'],'integer']
        ];
    }

    //处理我的团队信息
    public function getList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $exit = Share::find()->andWhere(['user_id' => $this->user_id, 'is_delete' => 0])->exists();
        $user = User::findOne(['id' => $this->user_id]);
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        if ($share_setting->level == 0) {
            return [
                'code' => 1,
                'msg' => '网络异常',
                'data' => []
            ];
        }
        if (!$exit || $user->is_distributor != 1) {
            return [
                'code' => 1,
                'msg' => '网络异常',
                'data' => []
            ];
        }

        $team = self::team($this->store_id, $this->user_id);
        $user_list = $team[1];
        $data = $team[0];


        if ($share_setting->level > 0 && $this->status == 1) {
            $data['list'] = $user_list['f_c'];
        }
        if ($this->status == 2 && $share_setting->level > 1) {
            $data['list'] = $user_list['s_c'];
        }
        if ($this->status == 3 && $share_setting->level > 2) {
            $data['list'] = $user_list['t_c'];
        }
        foreach ($data['list'] as $index => $value) {
            $data['list'][$index]['time'] = date('Y-m-d', $value['addtime']);
            $child_count = User::find()->where(['parent_id' => $value['id'], 'is_delete' => 0])->count();
            $data['list'][$index]['child_count'] = $child_count ? $child_count : 0;
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => $data
        ];
    }

    //获取我的团队信息  已废弃
    public static function team($store_id, $user_id)
    {
        $share_setting = Setting::findOne(['store_id' => $store_id]);
        $list = User::find()->alias('u')
            ->where(['and', ['u.is_delete' => 0, 'u.store_id' => $store_id], ['>', 'u.parent_id', 0]])
            ->leftJoin(Order::tableName() . ' o', "o.is_price=1 and o.user_id=u.id and o.parent_id = u.parent_id")
            ->andWhere([
                'or',
                ['o.is_delete' => 0, 'o.is_cancel' => 0],
                'isnull(o.id)'
            ])
            ->select([
                "sum(case when isnull(o.id) then 0 else o.pay_price end) price",
                'count(o.id) count',
                'u.nickname', 'u.addtime', 'u.parent_id', 'u.id', 'u.avatar_url'
            ])
            ->groupBy('u.id')
            ->asArray()->all();
        $user_list = array();
        $data = [];
        $data['first'] = 0;
        $data['second'] = 0;
        $data['third'] = 0;
        $data['list'] = [];
        $user_list['f_c'] = [];
        $user_list['s_c'] = [];
        $user_list['t_c'] = [];
        //获取用户下线的数量及订单情况
        foreach ($list as $index => $value) {
            if ($value['parent_id'] == $user_id) {
                $data['first']++;
                $user_list['f_c'][] = $value;
                if ($share_setting->level > 1) {
                    foreach ($list as $i => $v) {
                        if ($v['parent_id'] == $value['id']) {
                            $data['second']++;
                            $user_list['s_c'][] = $v;
                            if ($share_setting->level > 2) {
                                foreach ($list as $j => $item) {
                                    if ($item['parent_id'] == $v['id']) {
                                        $data['third']++;
                                        $user_list['t_c'][] = $item;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return [$data, $user_list];
    }

    /**
     * @return array
     * 获取分销订单明细 已废弃
     */
    public function GetOrder_1()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $team = self::team($this->store_id, $this->user_id);
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        $user_list = $team[1];
        $team_arr = [];
        $team_arr['id'] = [];
        $team_arr['id_1'] = [];
        $team_arr['f_c'] = [];
        $team_arr['s_c'] = [];
        $team_arr['t_c'] = [];
        $team_arr['id_1'][] = (string)$this->user_id;
        foreach ($user_list as $index => $value) {
            foreach ($value as $i => $v) {
                $team_arr['id'][] = $v['id'];
                $team_arr[$index][] = $v['id'];
                if ($index != 't_c') {
                    $team_arr['id_1'][] = $v['id'];
                }
            }
        }
        if ($this->limit == -1) {
            $first_price = Order::find()->alias('o')->where([
                'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
            ])->andWhere([
                'or',
                ['and', ['in', 'o.user_id', $team_arr['f_c']], ['o.parent_id' => $this->user_id, 'o.parent_id_1' => 0]],
                ['o.parent_id' => $this->user_id],
            ])->select(['sum(first_price)'])->scalar();
            $second_price = Order::find()->alias('o')->where([
                'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
            ])->andWhere([
                'or',
                ['and', ['in', 'o.user_id', $team_arr['s_c']], ['o.parent_id' => $team_arr['f_c'], 'o.parent_id_1' => 0]],
                ['o.parent_id_1' => $this->user_id],
            ])->select(['sum(second_price)'])->scalar();
            $third_price = Order::find()->alias('o')->where([
                'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
            ])->andWhere([
                'or',
                ['and', ['in', 'o.user_id', $team_arr['t_c']], ['o.parent_id' => $team_arr['s_c'], 'o.parent_id_1' => 0]],
                ['o.parent_id_2' => $this->user_id],
            ])->select(['sum(third_price)'])->scalar();
            $order_money = 0;
            if ($first_price) {
                $order_money += doubleval($first_price);
            }
            if ($second_price) {
                $order_money += doubleval($second_price);
            }
            if ($third_price) {
                $order_money += doubleval($third_price);
            }
            return doubleval(sprintf('%.2f', $order_money));
        }
        $query = Order::find()->alias('o')
            ->select([
                'o.*',
                'u.nickname', 'u.avatar_url'
            ])
            ->where(['o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id])
            ->joinWith('orderDetail')
            ->leftJoin(User::tableName() . ' u', 'o.user_id=u.id ')
            ->andWhere(['or',
                ['and', ['in', 'o.user_id', $team_arr['id']], ['o.parent_id_1' => 0, 'o.parent_id' => $team_arr['id_1']]],
                ['o.parent_id' => $this->user_id],
                ['o.parent_id_1' => $this->user_id],
                ['o.parent_id_2' => $this->user_id],
            ])->andWhere(['od.is_delete' => 0]);
        if ($this->status == 0) {//待付款
            $query->andWhere(['o.is_pay' => 0]);
        }
        if ($this->status == 1) {//已付款
            $query->andWhere(['o.is_pay' => 1, 'o.is_price' => 0]);
        }
        if ($this->status == 2) {//已完成
            $query->andWhere(['o.is_price' => 1]);
        }
        $query->groupBy('o.id');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $query->limit($pagination->limit)->offset($pagination->offset);
        $list = $query->orderBy('o.addtime DESC')->asArray()->all();

        $new_list = [];
        foreach ($list as $index => $value) {
            if ($value['parent_id_1'] == 0) {
                if (!in_array($value['user_id'], $team_arr['id']) && !in_array($value['parent_id'], $team_arr['id_1'])) {
                    continue;
                }
            }
            $new_list[$index]['order_no'] = $value['order_no'];
            $new_list[$index]['nickname'] = $value['nickname'];
            $new_list[$index]['avatar_url'] = $value['avatar_url'];
            $new_list[$index]['status'] = "待付款";
            $new_list[$index]['is_price'] = $value['is_price'];
            $refund = OrderRefund::findOne(['order_id' => $value['id'], 'is_delete' => 0, 'store_id' => $this->store_id]);
            if ($value['is_pay'] == 0) {
                $new_list[$index]['status'] = "待付款";
            } elseif ($value['is_pay'] == 1 && $value['is_price'] == 0) {
                $new_list[$index]['status'] = "已付款";
                if ($refund) {
                    if ($refund['status'] == 1) {
                        $new_list[$index]['status'] = "已退款";
                    } elseif ($refund['status'] == 0) {
                        $new_list[$index]['status'] = '售后申请中';
                    }
                }
            } elseif ($value['is_price'] == 1) {
                $new_list[$index]['status'] = "已完成";
            }
            foreach ($value['orderDetail'] as $i => $v) {
                $new_list[$index]['orderDetail'][$i]['num'] = $v['num'];
                $new_list[$index]['orderDetail'][$i]['name'] = $v['name'];
                $new_list[$index]['orderDetail'][$i]['goods_pic'] = Goods::getGoodsPicStatic($v['goods_id'])->pic_url;
            }

            if ($value['parent_id_1'] == 0) {
                if ($this->user_id == $value['parent_id']) {
                    $new_list[$index]['share_status'] = $share_setting->first_name ? $share_setting->first_name : "一级";
                    $new_list[$index]['share_money'] = $value['first_price'];
                } elseif (in_array($value['user_id'], $team_arr['s_c']) && in_array($value['parent_id'], $team_arr['f_c'])) {
                    $new_list[$index]['share_status'] = $share_setting->second_name ? $share_setting->second_name : "二级";
                    $new_list[$index]['share_money'] = $value['second_price'];
                } elseif (in_array($value['user_id'], $team_arr['t_c']) && in_array($value['parent_id'], $team_arr['s_c'])) {
                    $new_list[$index]['share_status'] = $share_setting->third_name ? $share_setting->third_name : "三级";
                    $new_list[$index]['share_money'] = $value['third_price'];
                }
            } else {
                if ($value['parent_id'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->first_name ? $share_setting->first_name : "一级";
                    $new_list[$index]['share_money'] = $value['first_price'];
                } elseif ($value['parent_id_1'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->second_name ? $share_setting->second_name : "二级";
                    $new_list[$index]['share_money'] = $value['second_price'];
                } elseif ($value['parent_id_2'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->third_name ? $share_setting->third_name : "三级";
                    $new_list[$index]['share_money'] = $value['third_price'];
                }
            }
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => $new_list,
        ];
    }

    //获取分销团队总人数
    public function getTeamCount()
    {
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        $this->share_setting = $share_setting;
        $team = [
            'f_c' => [],
            's_c' => [],
            't_c' => []
        ];
        if (!$share_setting || $share_setting->level == 0) {
            return [
                'team_count' => 0,
                'team' => $team
            ];
        }
        if ($share_setting->level == 4) {
            return [
                'team_count' => 0,
                'team' => $team
            ];
        }
        $first = User::find()->select(['id'])
            ->where(['store_id' => $this->store_id, 'parent_id' => $this->user_id, 'is_delete' => 0, 'type' => 1])->column();
        $count = count($first);
        $team['f_c'] = $first;
        if ($share_setting->level >= 2) {
            $second = User::find()->select(['id'])
                ->where(['store_id' => $this->store_id, 'parent_id' => $first, 'is_delete' => 0, 'type' => 1])->column();
            $count += count($second);
            $team['s_c'] = $second;
            if ($share_setting->level >= 3) {
                $third = User::find()->select(['id'])
                    ->where(['store_id' => $this->store_id, 'parent_id' => $second, 'is_delete' => 0, 'type' => 1])->column();
                $count += count($third);
                $team['t_c'] = $third;
            }
        }
        return [
            'team_count' => $count,
            'team' => $team
        ];
    }

    //2018-04-21获取分销订单总额
    public function getOrderCount()
    {
        $arr = $this->getTeamCount();
        $team_arr = $arr['team'];

        $sql = $this->getSql();

        $andWhere_1 = " WHERE al.user_id != {$this->user_id} AND al.parent_id_1 = {$this->user_id}";
        $order_money = 0;
        $order_money_un = 0;
        $select_1 = "SELECT al.first_price,al.is_price ";
        $first_price = \Yii::$app->db->createCommand($select_1 . $sql . $andWhere_1)->queryAll();
        if ($first_price) {
            foreach ($first_price as $index => $value) {
                $order_money += doubleval($value['first_price']);
                if ($value['is_price'] == 0) {
                    $order_money_un += doubleval($value['first_price']);
                }
            }
        }
        $team_f = '(' . implode(',', $team_arr['f_c']) . ')';
        $team_s = '(' . implode(',', $team_arr['s_c']) . ')';
        $team_t = '(' . implode(',', $team_arr['t_c']) . ')';
        if (!empty($team_arr['s_c'])) {
            $select_2 = "SELECT al.second_price,al.is_price ";
            $andWhere_2 = " WHERE al.user_id != {$this->user_id} AND
            ((al.user_id IN {$team_s} AND al.parent_id_1 IN {$team_f} AND al.parent_id_2 = 0)
            OR (al.parent_id_2 = {$this->user_id}))";
            $second_price = \Yii::$app->db->createCommand($select_2 . $sql . $andWhere_2)->queryAll();
            if ($second_price) {
                foreach ($second_price as $index => $value) {
                    $order_money += doubleval($value['second_price']);
                    if ($value['is_price'] == 0) {
                        $order_money_un += doubleval($value['second_price']);
                    }
                }
            }
        }
        if (!empty($team_arr['t_c'])) {
            $select_3 = "SELECT al.third_price,al.is_price ";
            $andWhere_3 = " WHERE al.user_id != {$this->user_id} AND
            ((al.user_id IN {$team_t} AND al.parent_id_1 IN {$team_s} AND al.parent_id_2 = 0)
            OR (al.parent_id_3 = {$this->user_id}))";
            $third_price = \Yii::$app->db->createCommand($select_3 . $sql . $andWhere_3)->queryAll();
            if ($third_price) {
                foreach ($third_price as $index => $value) {
                    $order_money += doubleval($value['third_price']);
                    if ($value['is_price'] == 0) {
                        $order_money_un += doubleval($value['third_price']);
                    }
                }
            }
        }
        $select = "SELECT al.rebate,al.is_price {$sql} WHERE al.rebate > 0 AND al.user_id = {$this->user_id}";
        $rebate = \Yii::$app->db->createCommand($select)->queryAll();
        if ($rebate) {
            foreach ($rebate as $index => $value) {
                $order_money += doubleval($value['rebate']);
                if ($value['is_price'] == 0) {
                    $order_money_un += doubleval($value['rebate']);
                }
            }
        }
        $arr['order_money'] = doubleval(sprintf('%.2f', $order_money));
        $arr['order_money_un'] = doubleval(sprintf('%.2f', $order_money_un));

        return $arr;
    }

    //获取分销订单详情
    public function getOrder()
    {

        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $arr = $this->getTeamCount();
        $team_arr = $arr['team'];
        $share_setting = $this->share_setting;
        if ($share_setting->level == 0) {
            return [
                'code'=>0,
                'msg'=>'',
                'data'=>[]
            ];
        }

        $first = '(' . implode(',', $team_arr['f_c']) . ')';
        $second = '(' . implode(',', $team_arr['s_c']) . ')';
        $third = '(' . implode(',', $team_arr['t_c']) . ')';
        $all = '(' . implode(',', array_merge($team_arr['f_c'], $team_arr['s_c'], $team_arr['t_c'])) . ')';

        $user_table = User::tableName();
        $sql = $this->getSql();
        $select = "SELECT al.*,u.nickname,u.avatar_url ";

        $where = " WHERE ((al.user_id = {$this->user_id} AND al.rebate > 0) ";
        if ($share_setting->level != 4) {
            $where .= "OR (al.user_id != {$this->user_id} ";
            if ($share_setting->level >= 1) {
                $where .= " AND (al.parent_id_1 = {$this->user_id}";
            }
            if ($share_setting->level >= 2) {
                $where .= " OR al.parent_id_2 = {$this->user_id} ";
            }
            if ($share_setting->level == 3) {
                $where .= " OR al.parent_id_3 = {$this->user_id} ";
            }
            if (!empty($team_arr['f_c']) && !empty($team_arr['s_c'])) {
                $where .= " OR (al.parent_id_2 = 0 AND al.user_id IN {$second} AND al.parent_id_1 IN {$first}) ";
            }
            if (!empty($team_arr['s_c']) && !empty($team_arr['t_c'])) {
                $where .= " OR (al.parent_id_2 = 0 AND al.user_id IN {$third} AND al.parent_id_2 IN {$second})";
            }
            $where .= "))";
        }
        $where .= ")";

        $sql = $sql . " LEFT JOIN {$user_table} AS u ON u.id=al.user_id {$where}";
        if ($this->status == 0) {
            $sql = $sql . " AND al.is_pay = 0 ";
        } elseif ($this->status == 1) {
            $sql = $sql . " AND al.is_pay = 1 AND al.is_price = 0 ";
        } elseif ($this->status == 2) {
            $sql = $sql . " AND al.is_price = 1 ";
        }
        $sql = $sql . " GROUP BY al.id , al.order_type ";

        $count = \Yii::$app->db->createCommand("select count(*)".$sql)->queryScalar();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);

        $list = \Yii::$app->db->createCommand($select . $sql . "ORDER BY al.addtime DESC LIMIT {$pagination->limit} OFFSET {$pagination->offset}")->queryAll();

        $new_list = [];
        foreach ($list as $index => $value) {
            $new_list[$index]['order_type'] = $value['order_type'];
            $new_list[$index]['order_no'] = $value['order_no'];
            $new_list[$index]['nickname'] = $value['nickname'];
            $new_list[$index]['avatar_url'] = $value['avatar_url'];
            $new_list[$index]['is_price'] = $value['is_price'];
            $new_list[$index]['status'] = "待付款";
            if ($value['is_pay'] == 0) {
                $new_list[$index]['status'] = "待付款";
            } elseif ($value['is_pay'] == 1 && $value['is_price'] == 0) {
                $new_list[$index]['status'] = "已付款";
            } elseif ($value['is_price'] == 1) {
                $new_list[$index]['status'] = "已完成";
            }
            $share_setting = $this->share_setting;
            if ($value['parent_id_2'] == 0) {
                if ($this->user_id == $value['parent_id_1']) {
                    $new_list[$index]['share_status'] = $share_setting->first_name ? $share_setting->first_name : "一级";
                    $new_list[$index]['share_money'] = $value['first_price'];
                } elseif (in_array($value['user_id'], $team_arr['s_c']) && in_array($value['parent_id_1'], $team_arr['f_c'])) {
                    $new_list[$index]['share_status'] = $share_setting->second_name ? $share_setting->second_name : "二级";
                    $new_list[$index]['share_money'] = $value['second_price'];
                } elseif (in_array($value['user_id'], $team_arr['t_c']) && in_array($value['parent_id_1'], $team_arr['s_c'])) {
                    $new_list[$index]['share_status'] = $share_setting->third_name ? $share_setting->third_name : "三级";
                    $new_list[$index]['share_money'] = $value['third_price'];
                }
            } else {
                if ($value['parent_id_1'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->first_name ? $share_setting->first_name : "一级";
                    $new_list[$index]['share_money'] = $value['first_price'];
                } elseif ($value['parent_id_2'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->second_name ? $share_setting->second_name : "二级";
                    $new_list[$index]['share_money'] = $value['second_price'];
                } elseif ($value['parent_id_3'] == $this->user_id) {
                    $new_list[$index]['share_status'] = $share_setting->third_name ? $share_setting->third_name : "三级";
                    $new_list[$index]['share_money'] = $value['third_price'];
                }
            }
            if ($value['rebate'] > 0 && $value['user_id'] == $this->user_id) {
                $new_list[$index]['share_status'] = "自购返现";
                $new_list[$index]['share_money'] = $value['rebate'];
            }


            //订单商品详情
            $new_list[$index]['orderDetail'] = [];
            if ($value['order_type'] == 's') {
                $new_list[$index]['orderDetail'] = $this->getOrderDetail($value['id']);
                $refund = OrderRefund::findOne(['order_id' => $value['id'], 'is_delete' => 0, 'store_id' => $this->store_id, 'type' => 1]);
                if($value['mch_id'] > 0){
                    $new_list[$index]['order_type'] = 'ds';
                }
            } elseif ($value['order_type'] == 'ms') {
                $new_list[$index]['orderDetail'] = $this->getMsOrderDetail($value['id']);
                $refund = MsOrderRefund::findOne(['order_id' => $value['id'], 'is_delete' => 0, 'store_id' => $this->store_id, 'type' => 1]);
            } elseif ($value['order_type'] == 'pt') {
                $new_list[$index]['orderDetail'] = $this->getPtOrderDetail($value['id']);
                $refund = PtOrderRefund::findOne(['order_id' => $value['id'], 'is_delete' => 0, 'store_id' => $this->store_id, 'type' => 1]);
            } elseif ($value['order_type'] == 'yy') {
                $new_list[$index]['orderDetail'] = $this->getYyOrderDetail($value['id']);
                $refund = null;
            } else {
                $new_list[$index]['orderDetail'] = [];
                $refund = null;
            }
            if ($refund) {
                if ($refund['status'] == 1) {
                    $new_list[$index]['status'] = "已退款";
                } elseif ($refund['status'] == 0) {
                    $new_list[$index]['status'] = '售后申请中';
                }
            }
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => $new_list
        ];
    }

    //订单详情--商城
    private function getOrderDetail($id)
    {
        $list = OrderDetail::find()->alias('od')->where(['od.order_id' => $id])
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
            ->select(['od.id', 'od.num', 'od.pic goods_pic', 'g.name', 'g.cover_pic'])
            ->asArray()->all();
        foreach ($list as $index => $value) {
            if (!$value['goods_pic']) {
                $list[$index]['goods_pic'] = $value['cover_pic'];
            }
        }
        return $list;
    }

    //订单详情--秒杀
    private function getMsOrderDetail($id)
    {
        $one = MsOrder::find()->alias('o')
            ->leftJoin(['g' => MsGoods::tableName()], 'g.id=o.goods_id')
            ->where(['o.store_id' => $this->store_id, 'o.id' => $id])
            ->select(['o.id', 'o.num', 'o.pic goods_pic', 'g.name'])->asArray()->one();
        $list[] = $one;
        return $list;
    }

    //订单详情--拼团
    public function getPtOrderDetail($id)
    {
        $list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->where(['od.order_id' => $id, 'od.is_delete' => 0])
            ->select(['od.id', 'od.num', 'od.pic goods_pic', 'g.name'])->asArray()->all();
        return $list;
    }

    public function getYyOrderDetail($id)
    {
        $list = YyOrder::find()->alias('o')
            ->leftJoin(['g' => YyGoods::tableName()], 'o.goods_id=g.id')
            ->where(['o.id' => $id, 'o.store_id' => $this->store_id])
            ->select(['o.id', 'g.cover_pic goods_pic', 'g.name'])->asArray()->all();
        foreach ($list as $index => $value) {
            $list[$index]['num'] = 1;
        }
        return $list;
    }

    //获取团队详情
    public function getTeam()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $arr = $this->getTeamCount();
        $team_arr = $arr['team'];
        $data = [];
        $data['first'] = count($team_arr['f_c']);
        $data['second'] = count($team_arr['s_c']);
        $data['third'] = count($team_arr['t_c']);
        if (empty($team_arr['f_c'])) {
            $data['list'] = [];
            return [
                'code' => 0,
                'msg' => '',
                'data' => $data
            ];
        }
        $first = '(' . implode(',', $team_arr['f_c']) . ')';
        $second = '(' . implode(',', $team_arr['s_c']) . ')';
        $third = '(' . implode(',', $team_arr['t_c']) . ')';

        $user_table = User::tableName();
        $sql = $this->getSql();
        $select = "( SELECT al.* ";
        $sql = $select . $sql;

        $query = "SELECT
                u.nickname, u.addtime,u.parent_id, u.id, u.avatar_url,count(al.id) count,
                sum(case when isnull(al.id) then 0 else al.pay_price end) price
                FROM {$user_table} AS u ";
        if ($this->status == 1) {
            $sql .= " WHERE al.parent_id_1 = {$this->user_id} AND al.is_price = 1 )";
            $query .= " LEFT JOIN {$sql} as al ON al.user_id =u.id
                WHERE u.store_id = {$this->store_id} AND u.id IN {$first} ";
        } elseif ($this->status == 2) {
            if (empty($team_arr['s_c'])) {
                $data['list'] = [];
                return [
                    'code' => 0,
                    'msg' => '',
                    'data' => $data
                ];
            }
            $sql .= " WHERE (
                (al.user_id IN {$second} AND al.parent_id_1 IN {$first} AND al.parent_id_2 = 0)
                OR (al.parent_id_2 = {$this->user_id})
             )AND al.is_price = 1)";
            $query .= " LEFT JOIN {$sql} as al ON al.user_id =u.id
                WHERE u.store_id = {$this->store_id}  AND u.id IN {$second}";
        } else {
            if (empty($team_arr['t_c']) || empty($team_arr['s_c'])) {
                $data['list'] = [];
                return [
                    'code' => 0,
                    'msg' => '',
                    'data' => $data
                ];
            }
            $sql .= " WHERE (
                ( al.user_id IN {$third} AND al.parent_id_2 IN {$second} AND al.parent_id_2 = 0)
                OR (al.parent_id_3 = {$this->user_id})
            ) AND al.is_price = 1)";
            $query .= " LEFT JOIN {$sql} as al ON al.user_id =u.id
                WHERE u.store_id = {$this->store_id}  AND u.id IN {$third}";
        }

        $count = \Yii::$app->db->createCommand($query)->query()->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = \Yii::$app->db->createCommand($query . " GROUP BY u.id ORDER BY u.addtime DESC LIMIT {$pagination->limit} OFFSET {$pagination->offset}")->queryAll();


        foreach ($list as $index => $value) {
            $list[$index]['time'] = date('Y-m-d', $value['addtime']);
            $child_count = User::find()->where(['parent_id' => $value['id'], 'is_delete' => 0])->count();
            $list[$index]['child_count'] = $child_count ? $child_count : 0;
        }
        $data['list'] = $list;

        return [
            'code' => 0,
            'msg' => '',
            'data' => $data
        ];
    }

    private function getSql()
    {
        $s_table = Order::tableName();
        $ms_table = MsOrder::tableName();
        $pt_table = PtOrder::tableName();
        $yy_table = YyOrder::tableName();
        $order_share_table = OrderShare::tableName();
        $sql_s = "(
            SELECT
                's' AS `order_type`,
                `id`,
                `order_no`,
                `is_pay`,
                `pay_price`,
                `is_price`,
                `user_id`,
                `apply_delete`,
                `addtime`,
                `parent_id` AS `parent_id_1`,
                `parent_id_1` AS `parent_id_2`,
                `parent_id_2` AS `parent_id_3`,
                `first_price`,
                `second_price`,
                `third_price`,
                `rebate`,
                `mch_id`,
                `store_id`,
                `is_recycle`,
                `is_show`
            FROM
                {$s_table}
            WHERE
            (`is_cancel` = 0)
            AND (`is_delete` = 0)
            AND (`parent_id` > 0 OR `rebate` != 0)
            AND (`mch_id` = 0 OR (`mch_id` > 0 AND `version` >= '2.7.2'))
            AND (store_id = {$this->store->id})
        )";
        $sql_ms = "(
            SELECT
                'ms' AS `order_type`,
                `id`,
                `order_no`,
                `is_pay`,
                `pay_price`,
                `is_price`,
                `user_id`,
                `apply_delete`,
                `addtime`,
                `parent_id` AS `parent_id_1`,
                `parent_id_1` AS `parent_id_2`,
                `parent_id_2` AS `parent_id_3`,
                `first_price`,
                `second_price`,
                `third_price`,
                `rebate`,
                0 as `mch_id`,
                `store_id`,
                `is_recycle`,
                `is_show`
            FROM
                {$ms_table}
            WHERE
                (`is_delete` = 0)
            AND (`is_sum` = 1)
            AND (`is_cancel` = 0)
            AND (`parent_id` > 0 OR `rebate` != 0)
            AND (store_id = {$this->store->id})
        )";
        $sql_pt = "(
            SELECT
                'pt' AS `order_type`,
                `pt`.`id`,
                `pt`.`order_no`,
                `pt`.`is_pay`,
                `pt`.`pay_price`,
                `pt`.`is_price`,
                `pt`.`user_id`,
                `pt`.`apply_delete`,
                `pt`.addtime,
                `os`.`parent_id_1`,
                `os`.`parent_id_2`,
                `os`.`parent_id_3`,
                `os`.`first_price`,
                `os`.`second_price`,
                `os`.`third_price`,
                `os`.`rebate`,
                0 as `mch_id`,
                `pt`.`store_id`,
                `pt`.`is_recycle`,
                `pt`.`is_show`
            FROM
                {$pt_table} `pt`
            LEFT JOIN {$order_share_table} `os` ON pt.id = os.order_id AND `os`.`type` = 0
            WHERE
                (`pt`.`is_delete` = 0)
            AND (`pt`.`is_cancel` = 0)
            AND (`os`.`parent_id_1` > 0 OR `os`.`rebate` != 0)
            AND (pt.store_id = {$this->store->id})
        )";
        $sql_yy = "(
            SELECT
                'yy' AS `order_type`,
                `yy`.`id`,
                `yy`.`order_no`,
                `yy`.`is_pay`,
                `yy`.`pay_price`,
                `yy`.`is_use` is_price,
                `yy`.`user_id`,
                `yy`.`apply_delete`,
                `yy`.addtime,
                `os`.`parent_id_1`,
                `os`.`parent_id_2`,
                `os`.`parent_id_3`,
                `os`.`first_price`,
                `os`.`second_price`,
                `os`.`third_price`,
                `os`.`rebate`,
                0 as `mch_id`,
                `yy`.`store_id`,
                `yy`.`is_recycle`,
                `yy`.`is_show`
            FROM
                {$yy_table} `yy`
            LEFT JOIN {$order_share_table} `os` ON os.order_id = yy.id AND `os`.`type` = 1
            WHERE
                (`yy`.`is_delete` = 0)
            AND (`yy`.`is_cancel` = 0)
            AND (`os`.`parent_id_1` > 0 OR `os`.`rebate` != 0)
            AND (yy.store_id = {$this->store->id})
        )";

        $sql = " FROM (
        SELECT * FROM ( {$sql_s} UNION {$sql_ms} UNION {$sql_pt} UNION {$sql_yy} ) AS `l`
        WHERE
            `l`.`store_id` = {$this->store_id}
            AND `l`.`is_show` = 1
            AND `l`.`is_recycle` = 0
        ) AS `al` ";

        return $sql;
    }
}
