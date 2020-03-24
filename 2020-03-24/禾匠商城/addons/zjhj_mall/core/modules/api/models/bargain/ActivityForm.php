<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/19
 * Time: 15:32
 */

namespace app\modules\api\models\bargain;


use app\hejiang\ApiResponse;
use app\models\ActivityMsgTpl;
use app\models\BargainGoods;
use app\models\BargainOrder;
use app\models\BargainSetting;
use app\models\BargainUserOrder;
use app\models\Goods;
use app\models\WechatTplMsgSender;
use app\modules\api\models\ApiModel;

/**
 * @property \app\models\BargainUserOrder[] $bargainUserList
 * @property \app\models\BargainGoods $bargain
 * @property \app\models\Goods $goods
 * @property \app\models\BargainOrder $bargainOrder
 */
class ActivityForm extends ApiModel
{
    public $store;
    public $user;
    public $order_id;

    public $limit;
    public $page;
    public $bargain;
    public $bargainOrder;
    public $bargainUserList;
    public $userCount;
    public $goods;

    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['limit'], 'default', 'value' => 3]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        /**
         * @var \app\models\BargainOrder $bargainOrder
         * @var \app\models\Goods $goods
         * @var \app\models\User $user
         */
        $bargainOrder = BargainOrder::find()->with('goods', 'user')->where([
            'id' => $this->order_id, 'store_id' => $this->store->id
        ])->one();
        $goods = $bargainOrder->goods;
        $user = $bargainOrder->user;
        $res = BargainOrder::getJoinOrderUser($this->order_id, $this->store->id, $this->limit, $this->page);

        $bargainPrice = $bargainOrder->original_price - $bargainOrder->min_price;
        if ($bargainPrice) {
            $moneyPer = round($res['money'] / $bargainPrice, 2) * 600;
        } else {
            $moneyPer = 0;
        }
        $moneyPerT = round($moneyPer * 74 / 100, 0);
        if ($moneyPerT > 464) {
            $moneyPerT = 464;
        }
        $bargainSetting = BargainSetting::findOne(['store_id' => $this->store->id]);
        $shareTitle = $bargainSetting->share_title;
        $shareTitle = str_replace("\r\n", "\n", $shareTitle);
        $shareTitles = explode("\n", $shareTitle);
        $shareTitle = $shareTitles[mt_rand(0, (count($shareTitles) - 1))];
        $data = [
            'goods_id' => $goods->id,
            'goods_name' => $goods->name,
            'cover_pic' => $goods->cover_pic,
            'order_user_avatar' => $user->avatar_url,
            'order_user_name' => $user->nickname,
            'original_price' => round($bargainOrder->original_price, 2),
            'min_price' => round($bargainOrder->min_price, 2),
            'status' => $bargainOrder->status,
            'reset_time' => $this->getTime($bargainOrder),
            'content' => '我发现一件好货，来一起砍价优惠购！',
            'price' => round($bargainOrder->original_price - $res['money'], 2),
            'bargain_info' => $res['bargain_info'],
            'money' => $res['money'],
            'money_per' => $moneyPer,
            'money_per_t' => $moneyPerT,
            'is_owner' => $bargainOrder->user_id == $this->user->id, // 是否是订单发起人
            'share_title' => $shareTitle
        ];

        return new ApiResponse(0, '成功', $data);

    }

    // 获取砍价剩余时间

    /**
     * @param $bargainOrder BargainOrder
     * @return int
     */
    private function getTime($bargainOrder)
    {
        if ($bargainOrder->status != 0) {
            return 0;
        } else {
            $addtime = $bargainOrder->addtime;
            $time = $bargainOrder->time;
            $second = time() - $addtime;
            $timeSecond = $time * 3600;
            if ($second >= $timeSecond) {
                return 0;
            }
            return intval($timeSecond - $second);
        }
    }

    // 用户砍价
    public function bargain()
    {
        $exit = BargainUserOrder::find()->where([
            'store_id' => $this->store->id, 'user_id' => $this->user->id,
            'order_id' => $this->order_id,
            'is_delete' => 0
        ])->exists();
        $data = [
            'bargain_status' => false
        ];
        if ($exit) {
            return new ApiResponse(0, '已砍价', $data);
        }
        $bargainOrder = BargainOrder::findOne(['id' => $this->order_id, 'is_delete' => 0]);

        if (!$bargainOrder) {
            return new ApiResponse(1, '砍价信息不存在或已删除');
        }
        if ($bargainOrder->status != 0) {
            return new ApiResponse(0, '已砍价', $data);
        }
        if ((time() - $bargainOrder->addtime) >= $bargainOrder->time * 3600) {
            return new ApiResponse(0, '砍价已结束', $data);
        }

        $this->bargain = BargainGoods::findOne(['goods_id' => $bargainOrder->goods_id, 'is_delete' => 0]);
        $this->bargainOrder = $bargainOrder;
        $this->goods = Goods::findOne(['id' => $bargainOrder->goods_id]);

        // 获取指定订单id的砍价参与人数
        $this->userCount = BargainUserOrder::getUserCount($this->store, $bargainOrder->id);

        $ok = true;
        while ($ok) {
            // 获取指定订单id的已砍价金额
            $totalPrice = BargainUserOrder::getPriceCount($this->store->id, $bargainOrder->id);

            // 商品可砍价金额
            $price = round($bargainOrder->original_price - $bargainOrder->min_price, 2);

            if ($totalPrice >= $price) {
                return new ApiResponse(0, '已砍至最低价', $data);
            }

            $userOrderPrice = $this->intToFloat($this->getPrice($price, $totalPrice));

            //当前已砍总价
            $totalPrice = round($totalPrice + $userOrderPrice, 2);

            if ($totalPrice <= $price) {
                $userOrder = new BargainUserOrder();
                $userOrder->store_id = $this->store->id;
                $userOrder->order_id = $this->order_id;
                $userOrder->user_id = $this->user->id;
                $userOrder->price = $userOrderPrice;
                $userOrder->is_delete = 0;
                $userOrder->addtime = time();
                $userOrder->save();
                $ok = false;
            }

            if ($totalPrice == $price) {
//                $msg_sender = new WechatTplMsgSender($this->getCurrentStoreId(), $bargainOrder->id, $this->getWechat(), 'BARGAIN');
//                $msg_sender->activitySuccessMsg('砍价', $this->goods->name, '商品已砍至最低价，赶快购买吧！');
                $msg_sender = new ActivityMsgTpl($this->getCurrentUserId(), 'BARGAIN');
                $msg_sender->activitySuccessMsg('砍价', $this->goods->name, '商品已砍至最低价，赶快购买吧！');
            }
        }

        $bargainPrice = round($userOrder->price, 2);

        $data = [
            'bargain_status' => true,
            'bargain_price' => $bargainPrice,
        ];

        return new ApiResponse(0, '砍价成功', $data);
    }

    // 砍价算法
    private function getPrice($price, $totalPrice)
    {
        if ($this->bargainOrder->status_data) {
            $data = \Yii::$app->serializer->decode($this->bargainOrder->status_data);
        } else {
            $data = \Yii::$app->serializer->decode($this->bargain->status_data);
        }
        $people = $this->userCount;
        if (isset($data->people)) {
            $dataPeople = intval($data->people);
            if ($data->people != 0) {
                if ($people == $dataPeople - 1) {
                    $money = ($price - $totalPrice) * 100;
                    return $money;
                }
                if ($people == $dataPeople) {
                    return 0;
                }
            }
        }
        if ($people < intval($data->human)) {
            $min = $data->first_min_money > $data->first_max_money ? $data->first_max_money : $data->first_min_money;
            $max = $data->first_min_money > $data->first_max_money ? $data->first_min_money : $data->first_max_money;
            $money = $this->getRand($min * 100, $max * 100);
        } else {
            $min = min($data->second_min_money, $data->second_max_money);
            $max = max($data->second_min_money, $data->second_max_money);
            $money = $this->getRand($min * 100, $max * 100);
        }
        if ($money > ($price - $totalPrice) * 100) {
            $money = (round($price - $totalPrice, 2)) * 100;
        }
        return intval($money);
    }

    // 随机数
    private function getRand($min, $max)
    {
        return round(mt_rand($min, $max), 2);
    }

    // int转float
    private function intToFloat($int)
    {
        if (count($int) < 3) {
            $int = str_pad($int, 3, 0, STR_PAD_LEFT);
        }
        return round(floatval($this->insertToStr($int, strlen($int) - 2, '.')), 2);
    }

    /**
     * 指定位置插入字符串
     * @param $str string  原字符串
     * @param $i  integer   插入位置
     * @param $substr string 插入字符串
     * @return string 处理后的字符串
     */
    private function insertToStr($str, $i, $substr)
    {
        //指定插入位置前的字符串
        $startstr = "";
        for ($j = 0; $j < $i; $j++) {
            $startstr .= $str[$j];
        }
        //指定插入位置后的字符串
        $laststr = "";
        for ($j = $i; $j < strlen($str); $j++) {
            $laststr .= $str[$j];
        }
        //将插入位置前，要插入的，插入位置后三个字符串拼接起来
        $str = $startstr . $substr . $laststr;
        //返回结果
        return $str;
    }
}