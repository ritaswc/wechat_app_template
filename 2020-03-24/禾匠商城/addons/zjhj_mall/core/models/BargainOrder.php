<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bargain_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $order_no
 * @property integer $goods_id
 * @property string $original_price
 * @property string $min_price
 * @property integer $time
 * @property integer $status
 * @property integer $is_delete
 * @property integer $addtime
 * @property string $attr
 * @property string $status_data
 */
class BargainOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bargain_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'goods_id', 'time', 'status', 'is_delete', 'addtime'], 'integer'],
            [['original_price', 'min_price'], 'number'],
            [['order_no', 'attr', 'status_data'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'order_no' => 'Order No',
            'goods_id' => 'Goods ID',
            'original_price' => '商品售价',
            'min_price' => '商品最低价',
            'time' => '砍价时间',
            'status' => '状态 0--进行中 1--成功 2--失败',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'attr' => 'Attr',
            'status_data' => '砍价方式数据',
        ];
    }

    // 查找指定用户指定商品是否存在砍价中的订单
    public static function getUserOrder($user_id, $goods_id, $store_id)
    {
        $order = BargainOrder::findOne([
            'user_id' => $user_id, 'goods_id' => $goods_id, 'store_id' => $store_id,
            'status' => 0, 'is_delete' => 0
        ]);
        if (!$order) {
            return false;
        }
        if ((time() - $order->addtime) >= $order->time * 3600) {
            $order->status = 2;
            $order->save();
            return self::getUserOrder($user_id, $goods_id, $store_id);
        }
        return $order;
    }

    // 砍价的商品
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    // 下单用户
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    // 砍价情况
    public function getOrderUser()
    {
        return $this->hasMany(BargainUserOrder::className(), ['order_id' => 'id']);
    }

    // 获取砍价剩余时间
    public function getTime()
    {
        if ($this->status != 0) {
            return 0;
        }
        $second = time() - $this->addtime;
        $timeSecond = $this->time * 3600;
        if ($second >= $timeSecond) {
            return 0;
        }
        return intval($timeSecond - $second);
    }

    // 获取参与砍价的用户
    /**
     * @param $limit
     * @param $page
     * @param $order_id
     * @param $store_id
     * @return array
     */
    public static function getJoinOrderUser($order_id, $store_id, $limit = 3, $page = 1)
    {
        /**
         * @var \app\models\BargainUserOrder[] $orders
         * @var \app\models\User $orderUser
         */
        $offset = $limit * ($page - 1);
        $query = BargainUserOrder::find()->with('user')->where([
            'order_id' => $order_id, 'store_id' => $store_id, 'is_delete' => 0
        ]);
        $query1 = clone $query;
        $money = $query1->select('sum(price)')->scalar();
        $orders = $query->limit($limit)->offset($offset)->all();

        $bargainInfo = [];
        foreach ($orders as $order) {
            $orderUser = $order->user;
            $newItem = [
                'avatar' => $orderUser->avatar_url,
                'nickname' => $orderUser->nickname,
                'price' => round($order->price, 2),
                'price_content' => "砍掉" . round($order->price, 2) . "元"
            ];
            $bargainInfo[] = $newItem;
        }

        return [
            'money' => round($money, 2),
            'bargain_info' => $bargainInfo
        ];
    }

    // 指定商品参与人数
    public static function getNum($goods_id)
    {
        return BargainOrder::find()->where(['goods_id' => $goods_id, 'is_delete' => 0])->count();
    }

    // 参与时间
    public function getAddtimeText()
    {
        return date('Y-m-d H:i', $this->addtime);
    }

    // 当前价
    public function getPrice()
    {
        $totalPrice = BargainUserOrder::getPriceCount($this->store_id,$this->id);
        $price = sprintf('%.2f',($this->original_price - $totalPrice));
        return $price <= $this->min_price ? $this->min_price : $price;
    }

    public function getStatusText()
    {
        switch($this->status){
            case 0:
                $text = "砍价中";
                break;
            case 1:
                $text = "砍价成功";
                break;
            case 2:
                $text = "砍价失败";
                break;
            default:
                $text = "";
        }
        return $text;
    }
}
