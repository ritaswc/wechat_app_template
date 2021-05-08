<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/24
 * Time: 16:23
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\Mch;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderMessage;
use app\models\PtOrder;
use app\models\User;
use app\models\YyOrder;
use yii\data\Pagination;

class OrderMessageForm extends MchModel
{
    public $store_id;
    public $limit;
    public $page;
    public $is_read;

    public function rules()
    {
        return [
            [['limit', 'page'], 'integer'],
            [['limit'], 'default', 'value' => 5],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = OrderMessage::find()->alias('om')->where([
            'om.store_id' => $this->store_id,
            'om.is_delete' => 0,
            'om.order_type' => [0, 1, 2, 3, 4]
        ]);
        if ($this->is_read) {
            $query->andWhere(['or',['om.is_read' => 0],['om.is_sound'=>0]]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->orderBy(['om.addtime' => SORT_DESC])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->asArray()
            ->all();

        $urlManager = \Yii::$app->urlManager;
        // 此处考虑在 cache 或 setting 内做缓存，存储 order_message 表的更新时间，以及查询结果
        // 插入 order_message 时，在 beforeInsert 内清空缓存
        // -- wi1dcard
        foreach ($list as $index => &$value) {
            switch ($value['order_type']) {
                case 3:
                    $yy_order = YyOrder::findOne(['id' => $value['order_id'], 'store_id' => $this->store_id]);
                    if ($value['type'] == 0) {
                        $value['url'] = $urlManager->createUrl(['mch/book/order/index', 'status' => 1]);
                    } else {
                        $value['url'] = $urlManager->createUrl(['mch/book/order/index', 'status' => 3]);
                    }
                    $user = User::findOne(['id' => $yy_order->user_id]);
                    $order['order_no'] = $yy_order->order_no;
                    $order['name'] = $user->nickname;
                    break;
                case 4:
                    $mch_goods = Goods::findOne(['id' => $value['order_id']]);
                    $mch = Mch::findOne(['id' => $mch_goods->mch_id]);
                    $value['url'] = $urlManager->createUrl(['mch/mch/goods/goods', 'keyword' => $mch_goods->name]);
                    $order['order_no'] = $mch_goods->name;
                    $order['name'] = $mch->name;
                    break;
                default:
                    if ($value['type'] == 0) {
                        $value['url'] = $urlManager->createUrl(['mch/order/index', 'status' => 1]);
                    } else {
                        $value['url'] = $urlManager->createUrl(['mch/order/refund']);
                    }
                    switch ($value['order_type']) {
                        case 0:
                            $class = "app\models\Order";
                            break;
                        case 1:
                            $class = "app\models\MsOrder";
                            break;
                        case 2:
                            $class = "app\models\PtOrder";
                            break;
                        default:
                            break;
                    }
                    $order = $class ? $class::findOne(['id' => $value['order_id'], 'store_id' => $this->store_id]) : [];
            }
            $value['order_no'] = $order['order_no'];
            $value['name'] = $order['name'];
            $value['platform'] = User::find()->where(['id' => $order['user_id']])->select('platform')->scalar();

            $time = time() - $value['addtime'];

            if ($time < 60) {
                $value['time'] = $time . '秒前';
            } elseif ($time < 3600) {
                $value['time'] = ceil($time / 60) . '分钟前';
            } elseif ($time < 86400) {
                $value['time'] = ceil($time / 3600) . '小时前';
            } else {
                $value['time'] = ceil($time / 86400) . '天前';
            }
        }

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }
}
