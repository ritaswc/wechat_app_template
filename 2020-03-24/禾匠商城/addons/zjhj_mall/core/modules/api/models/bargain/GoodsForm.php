<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/16
 * Time: 17:34
 */

namespace app\modules\api\models\bargain;


use app\hejiang\ApiResponse;
use app\models\BargainGoods;
use app\models\BargainOrder;
use app\models\Goods;
use app\models\TerritorialLimitation;
use app\modules\api\models\ApiModel;
use app\modules\api\models\StoreFrom;
use app\utils\GetInfo;

class GoodsForm extends ApiModel
{
    public $store;
    public $user;
    public $goods_id;
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['limit', 'page', 'goods_id'], 'integer'],
            [['limit'], 'default', 'value' => 3],
            [['page'], 'default', 'value' => 1],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        /**
         * @var $bargainGoods \app\models\BargainGoods
         * @var $goods \app\models\Goods
         */
        $goods = BargainGoods::getIdToGoods($this->goods_id, $this->store);
        if ($goods == null) {
            return new ApiResponse(1, '商品不存在', []);
        }
        if ($goods->status == 0) {
            return new ApiResponse(1, '商品未上架', []);
        }
        $bargainGoods = $goods->bargain;
        // 获取视频链接
        $resUrl = GetInfo::getVideoInfo($goods->video_url);
        // 距活动结束时间
        $resetTime = intval($bargainGoods->end_time - time());
        $foreshowTime = intval($bargainGoods->begin_time - time()) > 0 ? intval($bargainGoods->begin_time - time()) : 0;
        $newGoods = [
            'id' => $goods->id,
            'pic_list' => $goods->goodsPicList,
            'name' => $goods->name,
            'min_price' => round($bargainGoods->min_price, 2),
            'original_price' => round($goods->original_price, 2),
            'price' => round($goods->price, 2),
            'detail' => $goods->detail,
            'begin_time' => date('Y-m-d H:i', $bargainGoods->begin_time),
            'end_time' => date('Y-m-d H:i', $bargainGoods->end_time),
            'video_url' => $resUrl['url'],
            'attr_group_list' => $goods->getAttrGroupList(),
            'num' => $goods->getNum(),
            'reset_time' => $resetTime,
            'sale' => intval($goods->virtual_sales + BargainOrder::getNum($goods->id)),
            'content' => $this->getTerritorialLimitation(),
            'foreshow_time' => $foreshowTime
        ];

        // 砍价信息
        $bargainInfo = $this->getUserInfo();
        if ($bargainInfo) {
            $newGoods['reset_time'] = $bargainInfo['reset_time'];
        }

        $data = [
            'goods' => $newGoods,
            'flow' => $this->getFlow(),
            'bargain_info' => $bargainInfo
        ];
        return new ApiResponse(0, '', $data);
    }

    // 获取参与砍价的用户
    public function getUserInfo()
    {
        $bargainInfo = null;

        if ($this->user) {
            /**
             * @var \app\models\BargainOrder $order ;
             */
            $order = BargainOrder::getUserOrder($this->user->id, $this->goods_id, $this->store->id);
            if (!$order) {
                return $bargainInfo;
            }
            $bargainInfo = BargainOrder::getJoinOrderUser($order->id, $this->store->id, $this->limit, $this->page);
            $bargainInfo['reset_time'] = $order->getTime();
            $bargainInfo['price'] = round($order->original_price - $bargainInfo['money'], 2);
            $bargainInfo['money_per'] = round($bargainInfo['money'] / ($order->original_price - $order->min_price), 2) * 100;
            $bargainInfo['order_id'] = $order->id;
        }

        return $bargainInfo;
    }

    // 获取活动流程
    private function getFlow()
    {
        $imgForm = new StoreFrom();
        $imgForm->store = $this->store;
        $wxappImg = $imgForm->search();
        return [
            [
                'url' => $wxappImg['bargain']['bargain_goods']['click']['url'],
                'name' => '点击砍价',
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['jiantou']['url']
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['help']['url'],
                'name' => '找人帮砍',
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['jiantou']['url']
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['price']['url'],
                'name' => '价格合适',
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['jiantou']['url']
            ],
            [
                'url' => $wxappImg['bargain']['bargain_goods']['buy']['url'],
                'name' => '优惠购买',
            ],
        ];
    }

    private function getTerritorialLimitation()
    {
        $area = TerritorialLimitation::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'is_enable' => 1,
        ]);

        if($area){
            $address = [];
            $detail = json_decode($area->detail);
            if (!is_array($detail)) {
                $detail = [];
            }
            foreach ($detail as $key => $value) {
                foreach ($value->province_list as $key2 => $value2) {
                    $address[] = $value2->name;
                }
            }
            $str = implode('、',$address);
            $content = "本商品仅支持配送{$str}地区。请知悉";
        }else{
            $content = "";
        }

        return $content;
    }
}