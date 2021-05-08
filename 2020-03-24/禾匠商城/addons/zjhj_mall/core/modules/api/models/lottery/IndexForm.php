<?php
namespace app\modules\api\models\lottery;

use app\hejiang\ApiResponse;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;
use app\utils\GetInfo;

use app\models\Banner;
use app\models\LotteryGoods;
use app\models\LotteryLog;
use app\models\Goods;
use app\models\LotterySetting;

/**
 * @property \app\models\Store $store;
 */
class IndexForm extends ApiModel
{
    public $store_id;
    public $id;
    public $limit;
    public $page;
    public $user;

    public function rules()
    {
        return [
            [['limit', 'page', 'id'], 'integer'],
            [['limit'], 'default', 'value' => 5],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        $all_list = $this->getGoodsList();
        $data = [
            'banner_list' => $this->getBanner(),
            'goods_list' => $all_list[0],//版本
            'list' => $all_list[1],  ///版本
            'new_list' => $all_list[2],
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
        $banner_list = Banner::find()->where(['store_id' => $this->store_id, 'type' => 5, 'is_delete' => 0])->orderBy('sort ASC')->asArray()->all();

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

    // 获得商品信息
    private function getGoodsList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = LotteryGoods::find()->alias('l')->where([
                'AND',
                ['l.store_id' => $this->store_id],
                ['l.is_delete' => 0],
                ['l.status' => 1],
                ['l.type' => 0],
                ['<=','l.start_time',time()],
                ['not', ['g.id' => null]],
                ['>=','l.end_time',time()],
            ])
            ->joinwith(['goods g'=>function ($query) {
                        $query->where([
                            'g.is_delete' => 0,
                            'g.store_id' => $this->store_id,
                        ]);
            }])
            ->leftJoin([
                'll' => LotteryLog::find()
                    ->select('lottery_id, COUNT(1) lottery_log_count')
                    ->where([
                        'store_id' => $this->store_id,
                        'user_id' => $this->user->id,
                        'child_id' => 0
                    ])->groupBy('lottery_id'),
                ], 'll.lottery_id = l.id');

        $offset = $this->limit * ($this->page - 1);
        $list = $query->orderBy('ll.lottery_log_count ASC, id ASC,sort ASC,end_time DESC')->limit($this->limit)->offset($offset)->all();
        
        $other = [];
        $new_list = [];
        foreach ($list as $k => &$v) {
            $attr = json_decode($v->attr, true);
            $attr_id_list = array_reduce($attr, create_function('$v,$w', '$v[]=$w["attr_id"];return $v;'));
            
            if ($attr_id_list) {
                $original_price = $v->goods->getAttrInfo($attr_id_list)['price'];
                $new_list[$k] = [
                    'num'=> LotteryLog::find()->where(['store_id' => $this->store_id,'lottery_id' => $v->id,'child_id' => 0])->andWhere(['in','status',[0,1,2,3]])->count(),
                    'status' => LotteryLog::find()->where(['store_id' => $this->store_id,'lottery_id' => $v->id, 'user_id' => $this->user->id,'child_id' => 0])->one() == null,
                    'goods' => $v->goods,
                    'id' => $v->id,
                    'stock' => $v->stock,
                    'original_price' => $original_price == null ? 0 : $original_price,
                    'end_time' => $v->end_time,
                ];
            }

            ////////////////////版本兼容
            $other['num'][$k] = $new_list[$k]['num'];
            $other['status'][$k] = $new_list[$k]['status'];
            $other['end_time'][$k] = $new_list[$k]['end_time'];
            $v->goods->original_price = $new_list[$k]['original_price']; //
            $second = $v['end_time'] - time();
            $day = floor($second/(3600*24));
            $second = $second%(3600*24);
            $hour = floor($second/3600);
            $v->end_time = [$day,$hour];
            /////////////////////版本兼容
        }
        unset($v);
        array_multisort(array_column($new_list, 'status'), SORT_DESC, $new_list);
        $list = array($this->simplifyData($list),$other,$new_list);
        return $list;
    }

    private function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->goods) {
                $newData[$key]['goods'] = $val->goods;
            }
        }
        return $newData;
    }


    public function goods()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $id = $this->id;
        $query = LotteryGoods::find()->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'status' => 1,
                'id' => $id,
            ])->andWhere(['<=','start_time',time()])
                ->andWhere(['>=','end_time',time()])
                ->with(['goods'=>function ($query) {
                    $query->where([
                        'is_delete' => 0,
                        'store_id' => $this->store_id,
                    ]);
                }]);

        $lotteryGoods = $query->one();

        if (!$lotteryGoods) {
            return [
                'code' => 1,
                'msg' => '活动已结束'
            ];
        }

        $goods = $lotteryGoods->goods;

        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在'
            ];
        }
        // 获取视频链接
        $resUrl = GetInfo::getVideoInfo($goods->video_url);

        //原价
        $attr = json_decode($lotteryGoods->attr, true);
        $attr_id_list = array_reduce($attr, create_function('$v,$w', '$v[]=$w["attr_id"];return $v;'));
        $goods->original_price = $lotteryGoods->goods->getAttrInfo($attr_id_list)['price'];

        $num = LotteryLog::find()->where(['store_id' => $this->store->id,'lottery_id' => $id,'child_id' => 0])->count();

        $one = LotteryLog::find()->where(['store_id' => $this->store->id,'lottery_id' => $id, 'child_id' => 0, 'user_id' => $this->user->id])->one();
        $status = $one==null;

        $time = $lotteryGoods->end_time - time();

        $lucky_list = LotteryLog::find()->select('count(user_id) as lucky_num,user_id,child_id,status,lucky_code,addtime,lottery_id')->with(['user'=>function ($query) {
            $query->select('nickname,id,avatar_url,platform')->where([
                'is_delete' => 0,
                'store_id' => $this->store->id,
            ]);
        }])->where(['store_id' => $this->store->id,'lottery_id' => $id])
            ->andWhere(['not', ['status' => -1]])
            ->orderBy('addtime desc')
            ->limit(20)
            ->groupBy('user_id')
            ->having(['child_id' => 0])
            ->asArray()
            ->all();

        $newGoods = [
           'pic_list' => $goods->goodsPicList,
           'video_url' => $resUrl['url'],
           'name' => $goods->name,
           'num' => $num,
           'status' => $status,
           'original_price' => $goods->original_price ? $goods->original_price : 0,
           'detail' => $goods->detail,
           'id' => $goods->id,
           'time' => floor($time/86400).'天'.floor($time%86400/3600).'小时', //版本兼容 可删
        ];
        $data = [
            'goods' => $newGoods,
            'lottery_info' => $lotteryGoods,
            'lucky_list' => $lucky_list,
        ];
        return new ApiResponse(0, '', $data);
    }

    public function setting()
    {
        $setting = LotterySetting::find()->where(['store_id' => $this->store_id])->asArray()->one();
        return new ApiResponse(0, '', $setting);
    }
}
