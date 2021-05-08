<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/5
 * Time: 16:00
 */

namespace app\modules\api\models;


use app\hejiang\BaseApiResponse;
use app\modules\api\models\diy\DiyTemplateForm;
use app\utils\GetInfo;
use app\hejiang\ApiResponse;
use app\models\Banner;
use app\models\Cat;
use app\models\Coupon;
use app\models\FxhbHongbao;
use app\models\FxhbSetting;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\HomeBlock;
use app\models\HomeNav;
use app\models\HomePageModule;
use app\models\Mch;
use app\models\Miaosha;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\Option;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Store;
use app\models\Topic;
use app\models\User;
use app\models\UserCoupon;
use app\models\YyGoods;
use yii\helpers\VarDumper;

class IndexForm extends ApiModel
{
    public $store_id;
    public $_platform;
    public $page_id;

    public function search()
    {
        $store = $this->store;
        if (!$store)
            return new ApiResponse(1, 'Store不存在');
        if($this->page_id) {
            $model = new DiyTemplateForm();
            $model->attributes = \Yii::$app->request->get();
            $res = $model->detail();
            if($res['code'] == 0) {
                if($this->page_id == -1) {
                    $actList = $this->getActModalList();
                    $res['data']['act_modal_list'] = array_merge($res['data']['act_modal_list'], $actList);
                }
                return new BaseApiResponse($res);
            }
        }
        $this->getMiaoshaData();
        $banner_list = Banner::find()->where([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'type' => 1,
        ])->orderBy('sort ASC')->asArray()->all();
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

        $nav_icon_list = HomeNav::find()->where([
            'is_delete' => 0,
            'is_hide' => 0,
            'store_id' => $this->store_id,
        ])->orderBy('sort ASC,addtime DESC')->select('name,pic_url,url,name,open_type')->asArray()->all();

        $arr = ['/pages/web/authorization/authorization'];
        foreach ($nav_icon_list as $k => &$value) {
            if ($value['open_type'] == 'wxapp') {
                $res = $this->getUrl($value['url']);
                $value['appId'] = $res[2];
                $value['path'] = urldecode($res[4]);
            }
            // 去除支付宝不需要的菜单
            if ($this->_platform === 'my' && in_array($value['url'], $arr)) {
                unset($nav_icon_list[$k]);
            }
        }
        $nav_icon_list = array_values($nav_icon_list);
        unset($value);

        $module_list = $this->getModuleList($store);
        $cat_str = '';
        foreach ($module_list as $index => $value) {
            if ($value['name'] == 'cat') {
                $cat_str = 'all';
                break;
            }
            if ($value['name'] == 'single_cat') {
                $cat_str .= "cat_id={$value['cat_id']}&";
                $cat_id[] = $value['cat_id'];
            }
        }
        $cat_list_cache_key = md5('cat_list_cache_key&store_id=' . $this->store_id . $cat_str);
        $cat_list = \Yii::$app->cache->get($cat_list_cache_key);
        if (!$cat_list) {
            $query = Cat::find()->where([
                'is_delete' => 0,
                'parent_id' => 0,
                'store_id' => $this->store_id,
            ]);
            if ($cat_str != 'all') {
                $query->andWhere(['id' => $cat_id]);
            }
            $cat_list = $query->orderBy('sort ASC')->asArray()->all();
            foreach ($cat_list as $i => $cat) {
                $cat_list[$i]['page_url'] = '/pages/list/list?cat_id=' . $cat['id'];
                $cat_list[$i]['open_type'] = 'navigate';
                $cat_list[$i]['cat_pic'] = $cat['pic_url'];
                $goods_list_form = new GoodsListForm();
                $goods_list_form->store_id = $this->store_id;
                $goods_list_form->cat_id = $cat['id'];
                $goods_list_form->limit = $store->cat_goods_count;
                $goods_list_form_res = $goods_list_form->search();
                if ($goods_list_form_res->code == 0) {
                    $goods_list_data = new \ArrayObject($goods_list_form_res->data, \ArrayObject::ARRAY_AS_PROPS);
                    $goods_list = $goods_list_data['list'];
                } else {
                    $goods_list = [];
                }
                $cat_list[$i]['goods_list'] = $goods_list;
            }
            \Yii::$app->cache->set($cat_list_cache_key, $cat_list, 60 * 10);
        }

        $block_list = HomeBlock::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->all();
        $new_block_list = [];
        foreach ($block_list as $item) {
            $data = json_decode($item->data, true);
            foreach ($data['pic_list'] as &$value) {
                if ($value['open_type'] == 'wxapp') {
                    $res = $this->getUrl($value['url']);
                    $value['appId'] = $res[2];
                    $value['path'] = urldecode($res[4]);
                }
            }
            unset($value);
            $new_block_list[] = [
                'id' => $item->id,
                'name' => $item->name,
                'data' => $data,
                'style' => $item->style
            ];
        }
        $user_id = \Yii::$app->user->identity->id;
        $coupon_form = new CouponListForm();
        $coupon_form->store_id = $this->store_id;
        $coupon_form->user_id = $user_id;
        $coupon_list = $coupon_form->getList();

        $topicList = DiyTemplateForm::getTopic();
        $option = Option::getList('service,web_service,web_service_url,wxapp', $this->store_id, 'admin', '');
        foreach ($option as $index => $value) {
            if (in_array($index, ['wxapp'])) {
                $option[$index] = json_decode($value, true);
            }
        }
        $update_form = new HomePageModule();
        $update_form->store_id = $this->store_id;
        $update_list = $update_form->search_1();
        $newTopicList = [];
        $notice = Option::get('notice', $this->store_id, 'admin');
        foreach ($update_list as $index => $value) {
            if ($index == 'video') {
                foreach ($value as $k => $v) {
                    $res = GetInfo::getVideoInfo($v['url']);
                    if ($res && $res['code'] == 0) {
                        $update_list[$index][$k]['url'] = $res['url'];
                    }
                }
            }
            if($index == 'topic'){
                $newItem = [];
                foreach($topicList as $key => $topic){
                    $newItem[] = $topic;
                    if(count($newItem) > 0 && (count($newItem) == intval($value['count']) || $key == count($topicList) - 1)){
                        $newTopicList[] = $newItem;
                        $newItem = [];
                    }
                }
                $update_list[$index]['topic_list'] = $newTopicList;
            }
            if($index == 'notice') {
                $update_list[$index]['content'] = $notice;
            }
            if($index == 'coupon') {
                $update_list[$index]['coupon_list'] = $coupon_list;
            }
        }
        $update_list['banner']['banner_list'] = $banner_list;

        $mch_list = [];
        foreach ($module_list as &$m) {
            if ($m['name'] == 'mch') {
                $m['title'] = '好店推荐';
                $mch_list = $this->getMchList();
                break;
            }
        }
        unset($m);
        $data = [
            'module_list' => $module_list,
            'store' => StoreConfigForm::getData($store),
            'banner_list' => $banner_list,
            'nav_icon_list' => $nav_icon_list,
            'cat_goods_cols' => $store->cat_goods_cols,
            'cat_list' => $cat_list,
            'block_list' => $new_block_list,
            'coupon_list' => $coupon_list,
            'topic_list' => $newTopicList,
            'nav_count' => $store->nav_count,
            'notice' => $notice,
            'miaosha' => $this->getMiaoshaData(),
            'pintuan' => $this->getPintuanData(),
            'yuyue' => $this->getYuyueData(),
            'update_list' => $update_list,
            'act_modal_list' => $this->getActModalList(),
            'mch_list' => $mch_list,
            'template' => false
        ];

        return new ApiResponse(0, 'success', $data);
    }

    private function getBlockList()
    {

    }

    /**
     * @param Store $store
     */
    private function getModuleList($store)
    {
        $list = json_decode($store->home_page_module, true);
        if (!$list) {
            $list = [
                [
                    'name' => 'notice',
                ],
                [
                    'name' => 'banner',
                ],
                [
                    'name' => 'search',
                ],
                [
                    'name' => 'nav',
                ],
                [
                    'name' => 'topic',
                ],
                [
                    'name' => 'coupon',
                ],
                [
                    'name' => 'cat',
                ],
            ];
        } else {
            $new_list = [];
            foreach ($list as $item) {
                if (stripos($item['name'], 'block-') !== false) {
                    $names = explode('-', $item['name']);
                    $new_list[] = [
                        'name' => $names[0],
                        'block_id' => $names[1],
                    ];
                } elseif (stripos($item['name'], 'single_cat-') !== false) {
                    $names = explode('-', $item['name']);
                    $new_list[] = [
                        'name' => $names[0],
                        'cat_id' => $names[1],
                    ];
                } elseif (stripos($item['name'], 'video-') !== false) {
                    $names = explode('-', $item['name']);
                    $new_list[] = [
                        'name' => $names[0],
                        'video_id' => $names[1],
                    ];
                } else {
                    $new_list[] = $item;
                }
            }
            $list = $new_list;
        }
        return $list;
    }

    public function getMiaoshaData()
    {
        $ms_next = false;
        $miaosha = Miaosha::findOne([
            'store_id' => $this->store_id,
        ]);
        if (!$miaosha) {
            return [
                'code' => 1,
                'msg' => '暂无秒杀安排',
            ];
        }
        $miaosha->open_time = json_decode($miaosha->open_time, true);

        $list = MiaoshaGoods::find()->alias('mg')
            ->select('mg.id,g.name,g.cover_pic AS pic,g.original_price AS price,mg.attr,mg.start_time')
            ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
            ->where([
                'AND',
                [
                    'mg.is_delete' => 0,
                    'g.is_delete' => 0,
                    'mg.open_date' => date('Y-m-d'),
                    'g.status' => 1,
                    'mg.start_time' => date('H'),
                    'mg.store_id' => $this->store_id,
                ],
                ['in','mg.start_time', $miaosha->open_time],
            ])
            ->orderBy('g.sort ASC,g.addtime DESC')
            ->limit(10)
            ->asArray()->all();

        if (empty($list)) {
            $ms_next = true;
            $next = MiaoshaGoods::find()->alias('mg')
                ->where([
                    'AND',
                    [
                        'mg.is_delete' => 0,
                        'g.is_delete'  => 0,
                        'g.status' => 1,
                        'mg.store_id' => $this->store_id,
                    ],
                    ['=','mg.open_date',date('Y-m-d')],
                    ['>','mg.start_time', date('H')],
                ])->orWhere([
                    'AND',
                    [
                        'mg.is_delete' => 0,
                        'g.is_delete'  => 0,
                        'g.status' => 1,
                        'mg.store_id' => $this->store_id,
                    ],
                    ['>','mg.open_date',date('Y-m-d')],
                ])

                ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
                ->orderBy('mg.open_date asc,mg.start_time asc')
                ->select('mg.start_time,mg.open_date,mg.id')->one();

            $list = MiaoshaGoods::find()->alias('mg')
                ->select('mg.id,g.name,g.cover_pic AS pic,g.original_price AS price,mg.attr,mg.start_time,mg.open_date')
                ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
                ->where([
                    'AND',
                    [
                        'mg.is_delete' => 0,
                        'g.is_delete' => 0,
                        'mg.open_date' => $next->open_date,
                        'g.status' => 1,
                        'mg.start_time' => $next->start_time,
                        'mg.store_id' => $this->store_id,
                    ],
                    ['in','mg.start_time', $miaosha->open_time],
                ])
                ->orderBy('mg.open_date asc,mg.start_time asc')
                ->limit(10)
                ->asArray()
                ->all(); 

        }

        //////
        $startTime = intval(date('H'));
        $openDate = time();
        foreach ($list as $i => $item) {
            $item['attr'] = json_decode($item['attr'], true);
            $list[$i] = $item;
            $price_list = [];
            foreach ($item['attr'] as $attr) {
                if ($attr['miaosha_price'] <= 0) {
                    $price_list[] = doubleval($item['price']);
                } else {
                    $price_list[] = doubleval($attr['miaosha_price']);
                }
            }
            $list[$i]['price'] = number_format($list[$i]['price'], 2, '.', '');
            $list[$i]['miaosha_price'] = number_format(min($price_list), 2, '.', '');
            unset($list[$i]['attr']);
            $startTime = $item['start_time'];
            $openDate = strtotime($item['open_date']);
        };

        $openDate = $openDate>strtotime(date('Y-m-d',time()))?date('m.d',$openDate):'';
        if (count($list) == 0){
            return [
                'name' => '暂无秒杀活动',
                'rest_time' => 0,
                'goods_list' => null,
            ];
        }else{
            $name = $ms_next?'预告':'';
            return [
    //            'name' => intval(date('H')) . '点场',
                'name' => $startTime . '点场'.$name,
                'ms_next' => $ms_next,
                'date' => $openDate,
                'rest_time' => max(intval(strtotime(date('Y-m-d ' . $startTime . ':59:59')) - time()), 0),
                'goods_list' => $list,
            ];
        }
    }

    public function getPintuanData()
    {
        $num_query = PtOrderDetail::find()->alias('pod')
            ->select('pod.goods_id,SUM(pod.num) AS sale_num')
            ->leftJoin(['po' => PtOrder::tableName()], 'pod.order_id=po.id')
            ->where([
                'AND',
                [
                    'pod.is_delete' => 0,
                    'po.is_delete' => 0,
                    'po.is_pay' => 1,
                ],
            ])->groupBy('pod.goods_id');
        $list = PtGoods::find()->alias('pg')
            ->select('pg.*,pod.sale_num')
            ->leftJoin(['pod' => $num_query], 'pg.id=pod.goods_id')
            ->where([
                'AND',
                [
                    'pg.is_delete' => 0,
                    'pg.status' => 1,
                    'pg.store_id' => $this->store_id,
                ],
            ])->orderBy('pg.is_hot DESC,pg.sort ASC,pg.addtime DESC')
            ->limit(10)
            ->asArray()->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'id' => $item['id'],
                'pic' => $item['cover_pic'],
                'name' => $item['name'],
                'price' => number_format($item['price'], 2, '.', ''),
                'sale_num' => intval($item['sale_num'] ? $item['sale_num'] : 0) + intval($item['virtual_sales'] ? $item['virtual_sales'] : 0),
            ];
        }
        return [
            'goods_list' => $new_list,
        ];
    }

    /**
     * 获取首页活动弹窗列表
     */
    public function getActModalList()
    {
        $act_list = [];
        $fxhb_act = $this->getFxhbAct();
        if ($fxhb_act) {
            $act_list[] = $fxhb_act;
        }
        foreach ($act_list as $i => $item) {
            if ($i == 0)
                $act_list[$i]['show'] = true;
            else
                $act_list[$i]['show'] = false;
            $act_list[$i]['list'][] = $item;
        }
        return $act_list;
    }

    private function getFxhbAct()
    {
        $act_data = [
            'name' => '一起拆红包',
            'pic_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/fxhb/act_modal.png',
            'pic_width' => 750,
            'pic_height' => 696,
            'url' => '/pages/fxhb/open/open',
            'open_type' => 'navigate',
            'page_id' => 'fxhb'
        ];
        $fxhb_setting = FxhbSetting::findOne([
            'store_id' => $this->store_id,
        ]);
        if (!$fxhb_setting || $fxhb_setting->game_open != 1) {
            return null;
        }
        if ($user = \Yii::$app->user->isGuest) {
            return $act_data;
        }
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        /** @var FxhbHongbao $hongbao */
        $hongbao = FxhbHongbao::find()->where([
            'user_id' => $user->id,
            'store_id' => $this->store_id,
            'parent_id' => 0,
            'is_finish' => 0,
            'is_expire' => 0,
        ])->one();
        if (!$hongbao)
            return $act_data;
        if (time() > $hongbao->expire_time) {
            $hongbao->is_expire = 1;
            $hongbao->save();
            return $act_data;
        }
        return null;
    }

    public function getYuyueData()
    {
        $list = YyGoods::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'status' => 1])
            ->select(['id', 'name', 'cover_pic', 'price'])
            ->limit(10)->orderBy(['sort' => SORT_ASC])->asArray()->all();
        return $list;
    }

    public function getMchList()
    {
        $list = Mch::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'is_open' => 1,
            'is_lock' => 0,
            'is_recommend' => 1,
        ])->select('id,name,logo')
            ->orderBy('sort ASC,addtime DESC')->limit(10)
            ->asArray()->all();
        return $list ? $list : [];
    }

    private function getUrl($url)
    {
        preg_match('/^[^\?+]\?([\w|\W]+)=([\w|\W]*?)&([\w|\W]+)=([\w|\W]*?)$/', $url, $res);
        return $res;
    }

}