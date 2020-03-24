<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/4
 * Time: 14:39
 */

namespace app\modules\api\models\group;

use app\models\Article;
use app\models\Order;
use app\models\PtGoods;
use app\models\PtGoodsDetail;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtRobot;
use app\models\User;
use app\modules\api\models\ApiModel;

class GroupForm extends ApiModel
{
    public $oid;

    public $store_id;
    public $user_id;

    public function rules()
    {
        return [
            [['oid',], 'required'],
        ];
    }

    /**
     * 拼团详情
     */
    public function groupInfo()
    {
        $order  = PtOrder::findOne([
            'id' => $this->oid,
            'is_delete' => 0,
            'is_cancel' => 0,
            'is_group'  => 1,
        ]);
        if (!$order) {
            return [
                'code'  => 1,
                'msg'   => '订单不存在请重试1',
            ];
        }
        if ($order->is_pay == 0 && $order->pay_type != 2) {
            return [
                'code'=>1,
                'msg'=>'订单未支付'
            ];
        }
        if ($order->parent_id == 0) {
            $pid = $order->id;
        } else {
            $pid = $order->parent_id;
        }

        $OrderDetail = PtOrderDetail::findOne([
            'order_id'  => $pid,
            'is_delete' => 0
        ]);

        $goods = PtGoods::findOne([
            'id'        => $OrderDetail->goods_id,
            'status'    => 1,
            'is_delete' => 0,
        ]);
        if (!$goods) {
            return [
                'code'  => 1,
                'msg'   => '商品不存在或已下架',
            ];
        }
        if ($order->class_group) {
            $detail = PtGoodsDetail::findOne([
                'id'        => $order->class_group,
                'store_id'    => $this->store_id,
            ]);
            $goods->group_num = $detail->group_num;
      
            $attr_id_list=[];
            foreach (json_decode($OrderDetail['attr'], true) as $item) {
                array_push($attr_id_list, (int)$item['attr_id']);
            }
            $goods->price = $goods->getAttrInfo($attr_id_list, $order->class_group)['price'];
        }


        $new_goods = [
            'id'  => $goods->id,
            'name'  => $goods->name,
            'original_price'    => $goods->original_price,
            'price'             => $goods->price,
            'cover_pic'         => $goods->cover_pic,
            'group_num'         => $goods->group_num,
            'type'              => $goods->type,
            'one_buy_limit'     => $goods->one_buy_limit,
            'class_group'       => $order->class_group,
            'use_attr'          => $goods->use_attr,
        ];

        $attr_group_list = $goods->getAttrGroupList();

        $groupList = PtOrder::find()
            ->alias('o')
            ->select([
                'o.user_id',
                'o.order_no',
//                'u.avatar_url'
            ])
            ->andWhere(['or',['o.id'=>$pid],['o.parent_id'=>$pid]])
            ->andWhere(['o.is_delete'=>0,'o.is_group' => 1])
            ->andWhere(['or',['o.is_pay'=>1],['pay_type'=>2]])
//            ->leftJoin(['u'=>User::tableName()],'o.user_id=u.id')
            ->orderBy(['o.parent_id'=>'ASC','o.addtime' => 'DESC'])
            ->asArray()
            ->all();
        $limit_time_res = [
            'days'  => '00',
            'hours' => '00',
            'mins'  => '00',
            'secs'  => '00',
        ];
        $timediff = $order->limit_time - time();
        $groupFail = 0;
        if ($order->status==4) {
            $groupFail = 2;     // 拼团失败
        } elseif ($timediff<=0||$order->status==3) {
            $groupFail = 1;     // 拼团成功
        }

        $limit_time_res['days'] = intval($timediff/86400)>0?intval($timediff/86400):0;
        //计算小时数
        $remain = $timediff%86400;
        $limit_time_res['hours'] = intval($remain/3600)>0?intval($remain/3600):0;
        //计算分钟数
        $remain = $remain%3600;
        $limit_time_res['mins'] = intval($remain/60)>0?intval($remain/60):0;
        //计算秒数
        $limit_time_res['secs'] = $remain%60>0?$remain%60:0;
        $limit_time_ms = explode('-', date('Y-m-d-H-i-s', $order->limit_time));

        $surplus = $goods->group_num-count($groupList); // 剩余人数已

        for ($i = 0; $i< $new_goods['group_num']; $i++) {
            if (!isset($groupList[$i])) {
                $groupList[$i]['avatar_url'] = 0;
            }
        }
        $inGroup = false;
        foreach ($groupList as $k => $val) {
            if ($val['order_no']!='robot') {
                $groupList[$k]['avatar_url'] = User::find()->andWhere(['id'=>$val['user_id']])->select('avatar_url')->scalar();
                if (in_array($this->user_id, $val)) {
                    $inGroup = true;
                    continue;
                }
            } else {
                $groupList[$k]['avatar_url'] = PtRobot::find()->andWhere(['id'=>$val['user_id']])->select('pic')->scalar();
            }
        }

        $goodsList = PtGoods::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1, 'is_hot'=>1])
            ->andWhere(['or',['>','limit_time',time()],['limit_time'=>0]])
            ->orderBy('sort ASC')
            ->limit(3)
            ->asArray()
            ->all();

        // 获取拼团规则id
        $groupRuleId = Article::find()->andWhere([
            'store_id' => $this->store_id,
            'article_cat_id' => 3,
        ])->scalar();

        //$new_goods['num']=$goods->getNum(json_decode($OrderDetail['attr'], true));
        $new_goods['num']=$goods->getDNum($OrderDetail['goods_id']);
        if ($groupList) {
            return [
                'code'  => 0,
                'data'  => [
                    'goods' => $new_goods,
                    'groupList' => $groupList,
                    'surplus'   => $surplus,
                    'limit_time_res' => $limit_time_res,
                    'limit_time_ms' => $limit_time_ms,
                    'goodsList' => $goodsList,
                    'groupFail' => $groupFail,
                    'oid' => $pid,
                    'inGroup' => $inGroup,
                    'attr_group_list'=>$attr_group_list,
                    'groupRuleId'=>$groupRuleId,
                ],
            ];
        } else {
            return [
                'code'  => 1,
                'msg'   => '订单不存在请重试',
            ];
        }
    }
}
