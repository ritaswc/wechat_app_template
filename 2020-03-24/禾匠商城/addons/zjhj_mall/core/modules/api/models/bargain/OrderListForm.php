<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/23
 * Time: 9:20
 */

namespace app\modules\api\models\bargain;


use app\hejiang\ApiResponse;
use app\models\BargainOrder;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class OrderListForm extends ApiModel
{
    public $store;
    public $user;
    public $status;

    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit', 'status'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
            [['status'], 'default', 'value' => -1],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        /**
         * @var \app\models\BargainOrder[] $list
         * @var \app\models\BargainUserOrder[] $orderUsers
         * @var \app\models\Goods $goods
         */
        $list = $this->getList();

        $orderList = [];
        foreach ($list as $order) {
            $goods = $order->goods;
            $orderUsers = $order->orderUser;
            $userPrice = 0;
            foreach ($orderUsers as $orderUser) {
                $userPrice += round($orderUser->price, 2);
            }
            $price = round(($order->original_price - $userPrice - $order->min_price), 2);
            switch ($order->status) {
                case 0:
                    $content = '砍价中';
                    break;
                case 1:
                    $content = '砍价成功￥'.$price;
                    break;
                default:
                    $content = '砍价失败';
            }

            $orderItem = [
                'goods_name' => $goods->name,
                'cover_pic' => $goods->cover_pic,
                'reset_time' => $order->getTime(),
                'min_price' => $order->min_price,
                'original_price' => $order->original_price,
                'user_price' => $userPrice,
                'price' => $price,
                'status' => $order->status,
                'content' => $content,
                'order_id'=>$order->id
            ];
            $orderList[] = $orderItem;
        }

        return new ApiResponse(0, '获取成功', [
            'list' => $orderList
        ]);
    }

    // 获取指定用户砍价列表
    private function getList()
    {
        $query = BargainOrder::find()->where([
            'store_id' => $this->store->id, 'user_id' => $this->user->id, 'is_delete' => 0
        ])->with('goods', 'orderUser');

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);

        $list = $query->limit($p->limit)->offset($p->offset)->orderBy(['status' => SORT_ASC, 'addtime' => SORT_DESC])->all();
        return $list;
    }
}