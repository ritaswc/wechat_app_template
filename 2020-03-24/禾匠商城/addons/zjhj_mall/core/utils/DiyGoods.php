<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/10
 * Time: 16:18
 */

namespace app\utils;


use app\models\BargainGoods;
use app\models\Goods;
use app\models\GoodsCat;
use app\models\IntegralGoods;
use app\models\LotteryGoods;
use app\models\Mch;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\PtGoods;
use app\models\Shop;
use app\models\Store;
use app\models\Topic;
use app\models\YyGoods;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property Store $store
 */
class DiyGoods
{
    public $store;
    public $type;

    public $cat;
    public $page;
    public $limit = 8;

    public $idList;
    public $is_page = false;
    public $mch;
    public $keyword;

    // 初始化模板信息
    public static function getTemplate(&$template)
    {
        // 获取需要查找的id
        $list = [
            'goods' => [
                'id_list' => [],
                'list' => []
            ],
            'miaosha' => [
                'id_list' => [],
                'list' => []
            ],
            'pintuan' => [
                'id_list' => [],
                'list' => []
            ],
            'bargain' => [
                'id_list' => [],
                'list' => []
            ],
            'book' => [
                'id_list' => [],
                'list' => []
            ],
            'shop' => [
                'id_list' => [],
                'list' => []
            ],
            'lottery' => [
                'id_list' => [],
                'list' => []
            ],
            'mch' => [
                'id_list' => [],
                'list' => []
            ],
            'integral' => [
                'id_list' => [],
                'list' => []
            ],
            'topic' => [
                'id_list' => [],
                'list' => []
            ],
        ];
        foreach ($template as $value) {
            if (array_key_exists($value['type'], $list) && $value['param']['list']) {
                $type = $value['type'];
                if($type == 'shop') {
                    foreach ($value['param']['list'] as $item) {
                        if (!in_array($item['id'], $list[$type]['id_list'])) {
                            $list[$type]['id_list'][] = $item['id'];
                        }
                    }
                } else if($type == 'mch') {
                    foreach ($value['param']['list'] as $item) {
                        if (!in_array($item['id'], $list[$type]['id_list'])) {
                            $list[$type]['id_list'][] = $item['id'];
                        }
                        if ($item['goods_style'] == 2) {
                            foreach ($item['goods_list'] as $v) {
                                if (!in_array($v['id'], $list['goods']['id_list'])) {
                                    $list['goods']['id_list'][] = $v['id'];
                                }
                            }
                        }
                    }
                } else {
                    foreach ($value['param']['list'] as $item) {
                        if ($item['goods_style'] == 2) {
                            foreach ($item['goods_list'] as $v) {
                                if (!in_array($v['id'], $list[$type]['id_list'])) {
                                    $list[$type]['id_list'][] = $v['id'];
                                }
                            }
                        }
                    }
                }
            }
        }

        $ok = false;
        foreach ($list as $key => $value) {
            if(!empty($value['id_list'])) {
                $res = DiyGoods::getGoods($key, $value['id_list']);
                $list[$key]['list'] = $res['goods_list'];
                $ok = true;
            } else {
                $list[$key]['list'] = [];
            }
        }

        if ($ok) {
            foreach ($template as &$value) {
                if (array_key_exists($value['type'], $list) && $value['param']['list']) {
                    foreach ($value['param']['list'] as &$item) {
                        if($value['type'] == 'shop') {
                            if(is_array($list[$value['type']]['list'])) {
                                foreach ($list[$value['type']]['list'] as $goods) {
                                    if ($goods['id'] == $item['id']) {
                                        $item = $goods;
                                        break;
                                    }
                                }
                            }
                        } else if($value['type'] == 'mch') {
                            if(is_array($list[$value['type']]['list'])) {
                                foreach ($list[$value['type']]['list'] as $mch) {
                                    if ($mch['id'] == $item['id']) {
                                        $item['name'] = $mch['name'];
                                        $item['pic_url'] = $mch['pic_url'];
                                        $item['logo'] = $mch['pic_url'];
                                        $item['goods_count'] = $mch['goods_count'];
                                        break;
                                    }
                                }
                            }
                            if ($item['goods_style'] == 2) {
                                foreach ($item['goods_list'] as &$v) {
                                    if(is_array($list['goods']['list'])){
                                        foreach ($list['goods']['list'] as $goods) {
                                            if ($goods['id'] == $v['id']) {
                                                $v = $goods;
                                                break;
                                            }
                                        }
                                    }
                                }
                                unset($v);
                                $item['goods_list'] = $list['goods']['list'];
                            }
                        } else {
                            if ($item['goods_style'] == 2) {
                                $newList = [];
                                foreach ($item['goods_list'] as $v) {
                                    if(is_array($list[$value['type']]['list'])){
                                        foreach ($list[$value['type']]['list'] as $goods) {
                                            if ($goods['id'] == $v['id']) {
                                                $newList[] = $goods;
                                                break;
                                            }
                                        }
                                    }
                                }
                                $item['goods_list'] = $newList;
                            }
                        }
                    }
                    unset($item);
                }
            }
            unset($value);
        }
    }

    // 获取详情（商城、秒杀、拼团、砍价、积分商城、多商户等）
    public static function getGoods($type, $idList = [] , $cat = 0, $limit = 8, $isPage = false, $page = 1, $mch = false)
    {
        $form = new DiyGoods();
        $form->type = $type;
        $form->idList = $idList;
        $form->cat = $cat;
        $form->limit = $limit;
        $form->is_page = $isPage;
        $form->page = $page;
        $form->mch = $mch;
        $form->keyword = \Yii::$app->request->get('keyword');
        $form->store = \Yii::$app->getStore();
        $res = $form->getDetail();
        return $res;

    }

    // 获取具体信息
    public function getDetail()
    {
        $res = [];
        $type = $this->type;
        if (method_exists($this, $type)) {
            $res = $this->$type();
        }
        return $res;
    }

    private function goods()
    {
        $query = Goods::find()->alias('g')
            ->where(['g.store_id' => $this->store->id, 'g.is_delete' => 0, 'g.type' => 0, 'g.status' => 1]);
        if ($this->cat != 0) {
            $query->leftJoin(['gc' => GoodsCat::tableName()], 'gc.goods_id=g.id')
                ->andWhere([
                    'or',
                    ['gc.is_delete' => 0, 'gc.store_id' => $this->store->id, 'gc.cat_id' => $this->cat],
                    "isnull(gc.id) and g.cat_id = {$this->cat}"
                ])->groupBy('g.id')->orderBy(['g.sort' => SORT_ASC, 'g.addtime' => SORT_DESC]);
        }
        if($this->mch) {
            $query->andWhere(['g.mch_id' => $this->mch]);
        }
        if($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }

        if($this->idList) {
            $query->andWhere(['g.id' => $this->idList]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->orderBy(['g.sort' => SORT_ASC, 'g.id' => SORT_DESC])->all();

        $goodsList = [];
        /* @var Goods[] $list */
        foreach ($list as $key => $item) {
            $attrGroupList = json_decode(json_encode($item->getAttrGroupList()), true);
            $price = round($item->price, 2);
            $goodsList[] = [
                'attr' => json_decode($item['attr'], true),
                'attr_group_list' => $attrGroupList,
                'id' => $item->id,
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'price' => $price ? $price : 0,
                'original_price' => $item->original_price,
                'name' => $item->name,
                'page_url' => '/pages/goods/goods?id=' . $item->id,
                'use_attr' => $item->use_attr,
                'is_negotiable' => $item->is_negotiable,

                'price_content' => $item->is_negotiable == 0 ? '￥'.$price : '价格面议'
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function miaosha()
    {
        $date = date('Y-m-d', time());
        $time = date('H', time());
        $query = MiaoshaGoods::find()->alias('mg')->where([
            'mg.store_id' => $this->store->id, 'mg.is_delete' => 0
        ])->joinWith('msGoods g')->andWhere(['g.is_delete' => 0]);
        if($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }
        if($this->idList) {
            $query->andWhere(['mg.id' => $this->idList]);
        } else {
            $query->andWhere([
                'or',
                [
                    'and',
                    ['mg.open_date' => $date],
                    ['>=', 'mg.start_time', $time]
                ],
                [
                    'and',
                    ['>', 'mg.open_date', $date],
                ]
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();
        $goodsList = [];
        /* @var MiaoshaGoods[] $list */
        foreach ($list as $key => $item) {
            /* @var MsGoods $msGoods */
            $msGoods = $item->msGoods;
            $price = $msGoods->original_price;
            $attr = json_decode($item['attr'], true);
            foreach ($attr as &$value) {
                $value['num'] = $value['miaosh_num'];
                $value['price'] = $value['miaosh_price'];
                if ($price == 0 || ($price > 0 && round($price, 2) >= round($value['miaosha_price'], 2))) {
                    $price = $value['miaosha_price'];
                }
            }
            unset($value);
            $goodsList[] = [
                'attr' => $attr,
                'attr_group' => json_decode(json_encode($msGoods->getAttrGroupList()), true),
                'id' => $item->id,
                'goods_id' => $msGoods->id,
                'pic_url' => $msGoods->cover_pic,
                'cover_pic' => $msGoods->cover_pic,
                'price' => round($price, 2) ? round($price, 2) : 0,
                'original_price' => $msGoods->original_price,
                'name' => $msGoods->name,
                'page_url' => '/pages/miaosha/details/details?id=' . $item->id,
                'use_attr' => $msGoods->use_attr,
                'open_date' => $item->open_date,
                'start_time' => $item->start_time,
                'is_negotiable' => 0,

                'price_content' => '秒杀价:',
                'original_price_content' => '售价:',
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function pintuan()
    {
        $query = PtGoods::find()->where([
            'store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1
        ])->orderBy(['is_hot' => SORT_DESC, 'addtime' => SORT_DESC]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        if($this->idList) {
            $query->andWhere(['id' => $this->idList]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }
        $list = $query->all();
        $goodsList = [];
        /* @var PtGoods[] $list */
        foreach ($list as $key => $item) {
            $attr = json_decode($item['attr'], true);
            $goodsList[] = [
                'attr' => $attr,
                'attr_group' => json_decode(json_encode($item->getAttrGroupList()), true),
                'id' => $item->id,
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'price' => round($item->price, 2) ? round($item->price, 2) : 0,
                'original_price' => $item->original_price,
                'name' => $item->name,
                'page_url' => '/pages/pt/details/details?gid=' . $item->id,
                'use_attr' => $item->use_attr,
                'is_negotiable' => 0,

                'price_content' => $item->group_num . '人团',
                'original_price_content' => '单买价:',
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];

    }

    private function bargain()
    {
        $query = Goods::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1,'type' => 2])->with('bargain');

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        if($this->idList) {
            $query->andWhere(['id' => $this->idList]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->orderBy(['sort' => SORT_ASC, 'id' => SORT_DESC])->all();

        $goodsList = [];
        /* @var Goods[] $list */
        foreach ($list as $key => $item) {
            /* @var BargainGoods $goods*/
            $goods = $item->bargain;
            $goodsList[] = [
                'attr' => json_decode($item->attr, true),
                'attr_group' => json_decode(json_encode($item->getAttrGroupList()), true),
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'original_price' => $item->price,
                'name' => $item->name,
                'use_attr' => $item->use_attr,
                'id' => $item->id,
                'price' => round($goods->min_price, 2) ? round($goods->min_price, 2) : 0,
                'page_url' => '/bargain/goods/goods?goods_id=' . $goods->goods_id,
                'is_negotiable' => 0,

                'begin_time' => $goods->begin_time,
                'end_time' => $goods->end_time,
                'begin_time_text' => $goods->getBeginTimeText(),
                'end_time_text' => $goods->getEndTimeText(),

                'price_content' => '最低价:',
                'original_price_content' => '售价:',
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function book ()
    {
        $query = YyGoods::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        if($this->idList) {
            $query->andWhere(['id' => $this->idList]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();

        $goodsList = [];
        /* @var YyGoods[] $list */
        foreach ($list as $key => $item) {
            $price = round($item->price, 2);
            $goodsList[] = [
                'attr' => json_decode($item->attr, true),
                'attr_group' => json_decode(json_encode($item->getAttrGroupList()), true),
                'id' => $item->id,
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'price' => $price ? $price : 0,
                'original_price' => $item->original_price,
                'name' => $item->name,
                'page_url' => '/pages/book/details/details?id=' . $item->id,
                'use_attr' => $item->use_attr,
                'is_negotiable' => 0,

                'price_content' => $price ? '￥'.$price : '免费'
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function shop ()
    {
        $query = Shop::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        if($this->idList) {
            $query->andWhere(['id' => $this->idList]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();

        $shopList = [];
        /* @var Shop[] $list*/
        foreach($list as $item) {
            $shopList[] = [
                'id' => $item->id,
                'pic_url' => $item->pic_url,
                'name' => $item->name,
                'score' => $item->score,
                'mobile' => $item->mobile,
                'longitude' => $item->longitude,
                'latitude' => $item->latitude
            ];
        }

        return [
            'goods_list' => $shopList,
            'pagination' => $pagination
        ];
    }

    private function lottery()
    {
        $query = LotteryGoods::find()->alias('lg')->where([
            'and',
            ['lg.is_delete' => 0, 'lg.store_id' => $this->store->id, 'lg.status' => 1, 'lg.type' => 0,],
            ['<=','lg.start_time',time()],
            ['>=','lg.end_time',time()],
        ])->joinWith(['goods g'])->andWhere(['g.is_delete' => 0]);
        if($this->idList) {
            $query->andWhere(['lg.id' => $this->idList]);
        }
        if($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();
        $goodsList = [];
        /* @var LotteryGoods[] $list */
        foreach ($list as $key => $item) {
            /* @var Goods $goods */
            $goods = $item->goods;
            $price = $goods->price;
            $attr = json_decode($item['attr'], true);
            $goodsList[] = [
                'attr' => $attr,
                'attr_group' => json_decode(json_encode($goods->getAttrGroupList()), true),
                'id' => $item->id,
                'goods_id' => $goods->id,
                'pic_url' => $goods->cover_pic,
                'cover_pic' => $goods->cover_pic,
                'price' => round($price, 2) ? round($price, 2) : 0,
                'original_price' => $goods->original_price,
                'name' => $goods->name,
                'page_url' => '/lottery/goods/goods?id=' . $item->id,
                'use_attr' => $goods->use_attr,
                'end_time' => $item->end_time,
                'start_time' => $item->start_time,
                'is_negotiable' => 0,

                'price_content' => '抽奖:',
                'original_price_content' => '原价:',
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function mch()
    {
        $goodsQuery = Goods::find()->alias('g')->where('g.mch_id=m.id')
            ->andWhere(['g.status' => 1, 'g.is_delete' => 0, 'g.store_id' => $this->store->id])
            ->select('count(g.id)');
        $query = Mch::find()->alias('m')->where([
            'm.store_id' => $this->store->id, 'm.is_delete' => 0, 'm.is_open' => 1, 'm.is_lock' => 0]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->select(['*', 'goods_count' => $goodsQuery])->asArray()->all();
        $mchList = [];
        /* @var Mch[] $list*/
        foreach ($list as $item) {
            $mchList[] = [
                'id' => $item['id'],
                'mch_id' => $item['id'],
                'name' => $item['name'],
                'pic_url' => $item['logo'],
                'goods_count' => $item['goods_count'],
            ];
        }

        return [
            'goods_list' => $mchList,
            'pagination' => $pagination
        ];
    }

    private function integral()
    {
        $query = IntegralGoods::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();
        $goodsList = [];
        /* @var IntegralGoods[] $list */
        foreach ($list as $key => $item) {
            $price = round($item->price, 2) ? round($item->price, 2) : 0;
            $priceContent = "";
            if($item->integral) {
                $priceContent .= $item->integral.'积分';
            }
            if($price == 0) {
            } else {
                $priceContent .= '+￥'.$price;
            }
            $goodsList[] = [
                'attr' => json_decode($item['attr'], true),
                'attr_group' => json_decode(json_encode($item->getAttrGroupList()), true),
                'id' => $item->id,
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'price' => $price,
                'integral_content' => $priceContent,
                'original_price' => $item->original_price,
                'name' => $item->name,
                'page_url' => '/pages/integral-mall/goods-info/index?goods_id=' . $item->id,
                'use_attr' => $item->use_attr,
                'is_negotiable' => 0,

                'original_price_content' => '原价:￥'.$item->original_price
            ];
        }

        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    private function topic()
    {
        $query = Topic::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);

        if($this->cat != 0) {
            $query->andWhere(['type' => $this->cat]);
        }
        if($this->idList) {
            $query->andWhere(['id' => $this->idList]);
        }
        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        if($this->is_page) {
            $query->limit($pagination->limit)->offset($pagination->offset);
        }

        $list = $query->all();
        $goodsList = [];
        /* @var Topic[] $list */
        foreach ($list as $key => $item) {
            $read_count = intval($item['read_count'] + $item['virtual_read_count']);
            if ($read_count < 10000) {
                $read_count = $read_count;
            }
            if ($read_count >= 10000) {
                $read_count = intval($read_count / 10000) . '万+';
            }
            $goodsList[] = [
                'id' => $item->id,
                'goods_id' => $item->id,
                'pic_url' => $item->cover_pic,
                'cover_pic' => $item->cover_pic,
                'title' => $item->title,
                'name' => $item->title,
                'page_url' => '/pages/topic/topic?id=' . $item->id,
                'layout' => $item->layout,
                'read_count' => $read_count,
            ];
        }
        return [
            'goods_list' => $goodsList,
            'pagination' => $pagination
        ];
    }

    // diy组件权限
    public static function getDiyAuth()
    {
        $plugin = \Yii::$app->controller->getUserAuth() ? \Yii::$app->controller->getUserAuth() : [];
        if(in_array('integralmall', $plugin)) {
            array_push($plugin, 'integral');
        }
        $defaultAuth = ['search', 'nav', 'banner', 'notice', 'topic',
            'link', 'rubik', 'video' ,'goods', 'shop', 'time', 'line', 'ad', 'modal', 'float'];
        $plugin = array_merge($plugin, $defaultAuth);
        return $plugin;
    }
}