<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/16
 * Time: 14:00
 */

namespace app\modules\api\models\bargain;

use app\hejiang\ApiResponse;
use app\models\Banner;
use app\models\BargainGoods;
use app\models\BargainOrder;
use app\models\BargainSetting;
use app\models\Goods;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

/**
 * @property \app\models\Store $store;
 */
class IndexForm extends ApiModel
{
    public $store;

    public $limit;
    public $page;
 
    public function rules()
    {
        return [
            [['limit', 'page'], 'integer'],
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        $data = [
            'banner_list' => $this->getBanner(),
            'goods_list' => $this->getGoodsList()
        ];
        return new ApiResponse(0, '', $data);
    }

    private function getUrl($url)
    {
        preg_match('/^[^\?+]\?([\w|\W]+)=([\w|\W]*?)&([\w|\W]+)=([\w|\W]*?)$/', $url, $res);
        return $res;
    }

    private function getBanner()
    {
        $banner_list = Banner::find()->where(['store_id' => $this->store->id, 'type' => 4, 'is_delete' => 0])->orderBy('sort ASC')->asArray()->all();
        foreach ($banner_list as $i => $banner) {
            if (!$banner['open_type']) {
                $banner_list[$i]['open_type'] = 'navigate';
            }
            if ($banner['open_type'] == 'wxapp') {
                $res = $this->getUrl($banner['page_url']);
                $banner_list[$i]['appId'] = $res[2];
                $banner_list[$i]['path'] = urldecode($res[4]);
            }
        }
        return $banner_list;
    }

    // 获得砍价商品信息
    private function getGoodsList()
    {
        return $this->getNewList($this->getList());
    }

    // 获取砍价商品信息
    private function getList()
    {
        if (!$this->validate()) {
            return [];
        }

        $query = Goods::find()->with('bargain')->where([
            'type' => 2, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id, 'mch_id' => 0]);

        $offset = $this->limit * ($this->page - 1);


        $list = $query->orderBy(['sort' => SORT_ASC])->limit($this->limit)->offset($offset)->all();

        return $list;
    }

    /**
     * @var $list \app\models\Goods[]
     * @param array $list
     * @return array
     */
    // 重新排列商品信息
    private function getNewList(array $list)
    {
        $newList = [];
        foreach ($list as &$item) {
            /* @var $bargain \app\models\BargainGoods */
            $bargain = $item->bargain;
            $newItem = [
                'goods_id' => $item->id,
                'name' => $item->name,
                'original_price' => round($item->original_price, 2),
                'price' => round($item->price, 2),
                'min_price' => round($bargain->min_price, 2),
                'cover_pic' => $item->cover_pic,
                'num' => $item->getNum(),
                'user_list' => $this->getGoodsUser($item->id, $item->store_id),
                'sale' => intval($item->virtual_sales + BargainOrder::getNum($item->id))
            ];
            $newList[] = $newItem;
        }

        return $newList;
    }

    public function getSetting()
    {
        $bargainSetting = BargainSetting::find()->where(['store_id' => $this->store->id])->asArray()->one();
        return new ApiResponse(0, '', $bargainSetting);
    }

    // 获取指定商品发起砍价的用户
    private function getGoodsUser($goods_id, $store_id)
    {
        $userList = BargainOrder::find()->with('user')
            ->where(['goods_id' => $goods_id, 'store_id' => $store_id, 'is_delete' => 0])
            ->limit(3)->groupBy('user_id')->all();
        $newList = [];
        foreach ($userList as $user) {
            $newList[] = [
                'avatar_url' => $user->user->avatar_url,
                'id' => $user->user->id
            ];
        }
        return $newList;
    }
}
