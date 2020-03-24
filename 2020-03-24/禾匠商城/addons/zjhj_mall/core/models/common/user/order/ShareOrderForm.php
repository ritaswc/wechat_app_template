<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/5
 * Time: 19:01
 */

namespace app\models\common\user\order;


use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Share;
use app\models\User;
use yii\data\Pagination;

class ShareOrderForm extends Model
{
    public $store;
    public $mch;

    public $user_id;
    public $keyword;
    public $page;
    public $limit;
    public $platform;// 所属平台
    public $order_type;


    public function rules()
    {
        return [
            [['user_id', 'limit', 'page', 'platform'], 'integer'],
            [['keyword'], 'trim'],
            [['keyword', 'order_type'], 'string'],
            [['order_type'], 'in', 'range' => ['s']],
            [['platform'], 'in', 'range' => [0, 1]],
            [['limit'], 'default', 'value' => 20]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $orderQuery = Order::find()->alias('o')->joinWith([
            'user u', 'refund r', 'clerk c', 'shop s'
        ])->andWhere([
            'o.store_id' => $this->store->id,
            'o.mch_id' => $this->mch->id,
            'o.is_cancel' => 0,
            'o.is_delete' => 0,
            'o.is_recycle' => 0,
            'o.is_show' => 1,
        ])->andWhere([
            'or',
            ['>', 'o.parent_id', 0],
            ['>', 'o.rebate', 0]
        ])->andWhere(['>=', 'o.version', '2.7.2']);

        if($this->keyword){
            $orderQuery->andWhere([
                'or',
                ['like', 'o.order_no', $this->keyword],
                ['like', 'u.nickname', $this->keyword]
            ]);
        }
        if(isset($this->platform)){
            $orderQuery->andWhere(['u.platform' => $this->platform]);
        }
        $count = $orderQuery->count();
        $pagination = new Pagination(['totalCount' => $count, 'PageSize' => $this->limit]);

        $list = $orderQuery->limit($pagination->limit)->offset($pagination->offset)
            ->orderBy(['o.addtime' => SORT_DESC])->all();
        $newList = [];
        foreach($list as $value){
            foreach($value as $key => $item){
                $newItem[$key] = $item;
            }
            $newItem['order_type'] = 'ds';
            $newItem['nickname'] = $value['user']['nickname'];
            $newItem['platform'] = $value['user']['platform'];
            $newItem['order_type'] = 'ds';
            $newItem['goods_list'] = $this->getOrderGoodsList($value['id']);
            $newItem['refund'] = "";
            if(count($value['refund']) > 0){
                $newItem['refund'] = $value['refund']['status'];
            }
            if($value['is_offline'] == 1){
                if($value['clerk']){
                    $newItem['clerk_name'] = $value['clerk']['nickname'];
                }
                if($value['shop']){
                    $newItem['shop'] = $value['shop'];
                }
            }
            $newItem['share'] = null;
            $newItem['share_1'] = null;
            $newItem['share_2'] = null;
            if($value['parent_id'] != 0 && $value['parent_id'] != -1){
                $share = User::find()->alias('u')->where(['u.id' => $value['parent_id'], 'u.store_id' => $this->store->id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $newItem['share'] = $share;
            }
            if ($value['parent_id_1'] != 0 && $value['parent_id'] != -1) {
                $share_1 = User::find()->alias('u')->where(['u.id' => $value['parent_id_1'], 'u.store_id' => $this->store->id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $newItem['share_1'] = $share_1;
            }
            if ($value['parent_id_2'] != 0 && $value['parent_id'] != -1) {
                $share_2 = User::find()->alias('u')->where(['u.id' => $value['parent_id_2'], 'u.store_id' => $this->store->id, 'u.is_delete' => 0])
                    ->leftJoin(Share::tableName() . ' s', 's.user_id = u.id and s.is_delete=0')->select([
                        'u.nickname','u.platform', 's.name', 's.mobile'
                    ])->asArray()->one();
                $newItem['share_2'] = $share_2;
            }

            $newList[] = $newItem;
        }
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $newList,
        ];
    }

    private function getOrderGoodsList($order_id)
    {
        $order_detail_list = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name,g.unit')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $order_detail_list[$i]['attr_list'] =json_decode($order_detail['attr']);
        }
        return $order_detail_list;
    }
}