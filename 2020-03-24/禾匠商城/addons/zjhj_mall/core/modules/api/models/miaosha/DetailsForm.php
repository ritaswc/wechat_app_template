<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/3/12
 * Time: 10:31
 */

namespace app\modules\api\models\miaosha;


use app\models\common\CommonGoods;
use app\models\GoodsShare;
use app\models\Option;
use app\utils\GetInfo;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\MsGoodsPic;
use app\modules\api\models\ApiModel;
use PHP_CodeSniffer\Util\Common;

class DetailsForm extends ApiModel
{
    public $id;
    public $user_id;
    public $store_id;
    public $miaosha_goods;
    public $scene_type;  //1--表示扫描商品海报小程序码进入
    public $goods_id;

    public function rules()
    {
        return [
            [['user_id'], 'safe'],
            [['scene_type', 'id', 'goods_id'], 'integer']
        ];
    }

    /**
     * @return array
     * 秒杀商品详情
     */
    public function search()
    {
        if (!$this->validate())
            return $this->errorResponse;
        if ($this->id) {
            $this->miaosha_goods = MiaoshaGoods::findOne(['id' => $this->id]);
            if (!$this->miaosha_goods) {
                return [
                    'code' => 1,
                    'msg' => '商品不存在或已下架',
                ];
            }
            if ($this->scene_type == 1) {
                if ($this->miaosha_goods->start_time != intval(date('H')) || $this->miaosha_goods->open_date != date('Y-m-d')) {
                    $this->miaosha_goods = MiaoshaGoods::find()->where([
                        'goods_id' => $this->miaosha_goods->goods_id,
                        'is_delete' => 0,
                        'store_id' => $this->miaosha_goods->store_id
                    ])->andWhere(['or', ['and', ['open_date' => date('Y-m-d')], ['>=', 'start_time', intval(date('H'))]], ['>', 'open_date', date('Y-m-d')]])
                        ->orderBy(['open_date' => SORT_ASC, 'start_time' => SORT_ASC])->one();

                    if (!$this->miaosha_goods) {
                        return [
                            'code' => 1,
                            'msg' => '商品不存在或已下架',
                        ];
                    }
                }
            }
        }
        if ($this->goods_id) {
            $this->miaosha_goods = MiaoshaGoods::find()->where(['goods_id' => $this->goods_id, 'is_delete' => 0, 'store_id' => $this->store_id])
                ->andWhere(['or', ['and', ['open_date' => date('Y-m-d')], ['>=', 'start_time', intval(date('H'))]], ['>', 'open_date', date('Y-m-d')]])
                ->orderBy(['open_date' => SORT_ASC, 'start_time' => SORT_ASC])->one();
            if (!$this->miaosha_goods) {
                return [
                    'code' => 1,
                    'msg' => '商品暂无秒杀活动',
                ];
            }
        }
        $goods = MsGoods::findOne([
            'id' => $this->miaosha_goods->goods_id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
        ]);

        $msGoods = $goods;
        if (!$goods)
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        $pic_list = MsGoodsPic::find()->select('pic_url')->where(['goods_id' => $goods->id, 'is_delete' => 0])->asArray()->all();
        $is_favorite = 0;

        $service_list = explode(',', $goods->service);
        // 默认商品服务
        if (!$goods->service) {
            $option = Option::get('good_services', $this->store->id, 'admin', []);
            foreach ($option as $item) {
                if ($item['is_default'] == 1) {
                    $service_list = explode(',', $item['service']);
                    break;
                }
            }
        }
        $new_service_list = [];
        if (is_array($service_list))
            foreach ($service_list as $item) {
                $item = trim($item);
                if ($item)
                    $new_service_list[] = $item;
            }
        $res_url = GetInfo::getVideoInfo($goods->video_url);
        $goods->video_url = $res_url['url'];
        $miaosha = $this->getMiaoshaData($goods->id);
        $miaosha_data = $miaosha['miaosha_data'];
        if ($miaosha_data) {
            $miaosha_data['miaosha_price'] = sprintf('%.2f', $miaosha_data['miaosha_price']);
            $miaosha_data['rest_num'] = min((int)$goods->getNum(), (int)$miaosha_data['miaosha_num']) - $miaosha_data['sell_num'];
        }
        $miaosha['miaosha_data'] = $miaosha_data;

        $old = [];
        $new = [];

        foreach (json_decode($this->miaosha_goods->attr) as $v) {
            if ($v->price > 0) {
                $old[] = $v->price;
            } else {
                $old[] = $goods->original_price;
            }
            if ($v->miaosha_price > 0) {
                $new[] = $v->miaosha_price;
            } else {
                if ($v->price > 0) {
                    $new[] = $v->price;
                } else {
                    $new[] = $goods->original_price;
                }

            }
        };

        $miaosha['old_small_price'] = sprintf('%.2f', min($old));
        $miaosha['old_big_price'] = sprintf('%.2f', max($old));
        $miaosha['new_small_price'] = sprintf('%.2f', min($new));
        $miaosha['new_big_price'] = sprintf('%.2f', max($new));

        // 获取最高分销价 、最低会员价、当前会员价
        $goodsShare = GoodsShare::find()->where(['type' => GoodsShare::SHARE_GOODS_TYPE_MS, 'relation_id' => $this->miaosha_goods->id])->one();
        $res = CommonGoods::getMMPrice([
            'attr' => $this->miaosha_goods->attr,
            'attr_setting_type' => $goodsShare['attr_setting_type'],
            'share_type' => $goodsShare['share_type'],
            'share_commission_first' => $goodsShare['share_commission_first'],
            'price' => $goods['original_price'],
            'individual_share' => $goodsShare['individual_share'],
//            'is_level' => $goods['is_discount'],
            'is_level' => $this->miaosha_goods->is_level,
        ], ['type' => 'MIAOSHA']);

        $attr = json_decode($goods['attr'], true);
        $goodsPrice = $goods->original_price;
        $isMemberPrice = false;
        // 秒杀特殊
        if (count($attr) === 1 && $attr[0]['attr_list'][0]['attr_name'] == '默认') {
            $goodsPrice = $res['user_is_member'] ? $res['min_member_price'] : $miaosha['new_small_price'];
            $isMemberPrice = $res['user_is_member'];
        }

        $res_url = GetInfo::getVideoInfo($goods->video_url);

        return [
            'code' => 0,
            'data' => (object)[
                'id' => $goods->id,
                'attr' => $goods->attr,
                'pic_list' => $pic_list,
                'cover_pic' => $goods->cover_pic,
                'attr_pic' => $pic_list[0]['pic_url'],
                'name' => $goods->name,
                'price' => sprintf('%.2f', $goodsPrice),
                'detail' => $goods->detail,
                'sales' => $goods->getSalesVolume() + $goods->virtual_sales,
                'attr_group_list' => $goods->getAttrGroupList(),
                'num' => $goods->getNum(),
                'is_favorite' => $is_favorite,
                'service_list' => $new_service_list,
                'original_price' => sprintf('%.2f', $goods->original_price),
                'video_url' => $res_url['url'],
                'unit' => $goods->unit,
                'miaosha' => $miaosha,
                'use_attr' => intval($goods->use_attr),
                'max_share_price' => sprintf('%.2f', $res['max_share_price']) > 0 ? sprintf('%.2f', $res['max_share_price']) : 0,
                'min_member_price' => sprintf('%.2f', $res['min_member_price']) > 0 ? sprintf('%.2f', $res['min_member_price']) : $goods['original_price'],
                'is_share' => $res['is_share'],
                'is_level' => $res['is_level'],
                'is_member_price' => $isMemberPrice,
            ]
        ];
    }

    //获取商品秒杀数据
    public function getMiaoshaData($goods_id)
    {
        $miaosha_goods = $this->miaosha_goods;
        $attr_data = json_decode($miaosha_goods->attr, true);
        $total_miaosha_num = 0;
        $total_sell_num = 0;
        $miaosha_price = 0.00;
        foreach ($attr_data as $i => $attr_data_item) {
            $total_miaosha_num += $attr_data_item['miaosha_num'];
            $total_sell_num += $attr_data_item['sell_num'];
            if ($miaosha_price == 0) {
                $miaosha_price = $attr_data_item['miaosha_price'];
            } else {
                $miaosha_price = min($miaosha_price, $attr_data_item['miaosha_price']);
            }
        }
        $miaosha_data = null;
        if (count($attr_data) == 1) {
            $miaosha_data = $attr_data[0];
        }
        return [
            'miaosha_num' => $total_miaosha_num,
            'sell_num' => $total_sell_num,
            'miaosha_price' => (float)$miaosha_price,
            'begin_time' => strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':00:00'),
            'end_time' => strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':59:59'),
            'now_time' => time(),
            'buy_max' => $miaosha_goods->buy_max,
            'buy_limit' => $miaosha_goods->buy_limit,
            'miaosha_data' => $miaosha_data,
            'miaosha_goods_id' => $this->miaosha_goods->id
        ];
    }


}
