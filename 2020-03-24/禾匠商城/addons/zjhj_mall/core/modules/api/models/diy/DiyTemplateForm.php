<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\api\models\diy;

use app\models\Coupon;
use app\models\DiyPage;
use app\models\Topic;
use app\modules\api\models\ApiModel;
use app\modules\api\models\CouponListForm;
use app\utils\DiyGoods;
use app\utils\GetInfo;

class DiyTemplateForm extends ApiModel
{
    public $page_id;

    public static $topicList;
    private $couponList;
    private $integralCouponList;

    public function rules()
    {
        return [
            [['page_id'], 'integer']
        ];
    }

    public function detail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $actModalList = [];
        if ($this->page_id != -1) {
            $page = DiyPage::findOne(['id' => $this->page_id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id]);
        } else {
            $page = DiyPage::findOne(['is_delete' => 0, 'status' => 1, 'is_index' => 1, 'store_id' => $this->store->id]);
        }
        if (!$page) {
            return [
                'code' => 1,
                'msg' => '页面不存在'
            ];
        }

        $detail = $page->getTemplate()->asArray()->one();

        if ($detail) {
            $detail['template'] = ($detail['template'] == 'null' || !$detail['template']) ? [] : json_decode($detail['template'], true);
            DiyGoods::getTemplate($detail['template']);

            $timeAll = [];
            foreach ($detail['template'] as $index => &$item) {
                switch ($item['type']) {
                    case 'topic':
                        // 专题
                        $newTopicList = [];
                        if ($item['param']['style'] == 0) {
                            $item['param']['logo_1'] = $item['param']['logo_1'] ? $item['param']['logo_1'] : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic-1.png';
                            $item['param']['logo_2'] = $item['param']['logo_2'] ? $item['param']['logo_2'] : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic.png';
                            $item['param']['heated'] = $item['param']['heated'] ? $item['param']['heated'] : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/home-page/icon-topic-r.png';
                            $topicList = self::getTopic();
                            $newItem = [];
                            foreach ($topicList as $key => $topic) {
                                $newItem[] = $topic;
                                if (count($newItem) > 0 && (count($newItem) == intval($item['param']['count']) || $key == count($topicList) - 1)) {
                                    $newTopicList[] = $newItem;
                                    $newItem = [];
                                }
                            }
                        } else {
                            $item['param']['cat_index'] = 0;
                            $list = [];
                            foreach ($item['param']['list'] as $key => $value) {
                                if ($value['cat_id'] && $value['goods_style'] != 2) {
                                    $res = DiyGoods::getGoods('topic', [], $value['cat_id'], 10, true);
                                    $goodsList = $res['goods_list'];
                                    if ($value['goods_style'] == 1) {
                                        array_splice($goodsList, $value['goods_num']);
                                    }
                                    $value['goods_list'] = $goodsList;
                                }
                                if (count($value['goods_list']) > 0) {
                                    $list[] = $value;
                                }
                            }
                            $item['param']['list'] = $list;
                        }
                        $item['param']['topic_list'] = $newTopicList;
                        break;
                    case 'video':
                        $res = GetInfo::getVideoInfo($item['param']['url']);
                        if ($res && $res['code'] == 0) {
                            $item['param']['url'] = $res['url'];
                        }
                        $item['param']['id'] = $index;
                        break;
                    case 'coupon':
                        $item['param']['coupon_list'] = $this->getCoupon();
                        break;
                    case 'banner':
                        foreach ($item['param']['list'] as &$value) {
                                if ($value['open_type'] == 'wxapp') {
                                    $res = self::getUrl($value['page_url']);
                                    $value['appId'] = $res[2];
                                    $value['path'] = urldecode($res[4]);
                                }
                        }
                        unset($value);
                        $item['param']['banner_list'] = $item['param']['list'];
                        break;
                    case 'nav':
                        foreach ($item['param']['default_list'] as &$v) {
                            foreach ($v as &$value) {
                                if ($value['open_type'] == 'wxapp') {
                                    $res = self::getUrl($value['url']);
                                    $value['appId'] = $res[2];
                                    $value['path'] = urldecode($res[4]);
                                }
                            }
                            unset($value);
                        }
                        unset($v);
                        $item['param']['nav_list'] = $item['param']['default_list'];
                        break;
                    case 'rubik':
                        foreach ($item['param']['list'] as &$value) {
                            if (isset($value['open_type']) && $value['open_type'] == 'wxapp') {
                                $res = self::getUrl($value['url']);
                                $value['appId'] = $res[2];
                                $value['path'] = urldecode($res[4]);
                            }
                        }
                        unset($value);
                        $item['param']['nav_list'] = $item['param']['list'];
                        break;
                    case 'link':
                        if (isset($item['param']['open_type']) && $item['param']['open_type'] == 'wxapp') {
                            $res = self::getUrl($item['param']['url']);
                            $item['param']['appId'] = $res[2];
                            $item['param']['path'] = urldecode($res[4]);
                        }
                        break;
                    case 'goods':
                        $param = &$item['param'];
                        $list = [];
                        foreach ($param['list'] as $key => &$value) {
                            if ($value['cat_id'] && $param['list'][$key]['goods_style'] != 2) {
                                $res = DiyGoods::getGoods('goods', [], $param['list'][$key]['cat_id'], 30, true);
                                $goodsList = $res['goods_list'];
                                if ($param['list'][$key]['goods_style'] == 1) {
                                    array_splice($goodsList, $param['list'][$key]['goods_num']);
                                }
                                $value['goods_list'] = $goodsList;
                            }
                            if (count($value['goods_list']) > 0) {
                                $list[] = $value;
                            }
                        }
                        $param['list'] = $list;
                        break;
                    case 'time':
                        $startTime = strtotime($item['param']['date_start']) - time();
                        $endTime = strtotime($item['param']['date_end']) - time();
                        $item['param']['start_time'] = ($startTime) <= 0 ? 0 : ($startTime);
                        $item['param']['end_time'] = ($endTime) <= 0 ? 0 : ($endTime);
                        $timeAll['time_' . $index] = $item;
                        break;
                    case 'modal':
                        $item['param']['page_id'] = $page->id;
                        $newList = [];
                        foreach ($item['param']['list'] as &$value) {
                            if ($value['open_type'] == 'wxapp') {
                                $res = self::getUrl($value['url']);
                                $value['appId'] = $res[2];
                                $value['path'] = urldecode($res[4]);
                            }
                            if ($value['pic_url']) {
                                $newList[] = $value;
                            }
                        }
                        $item['param']['list'] = $newList;
                        unset($value);
                        $actModalList[] = $item['param'];
                        break;
                    case 'miaosha':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : '马上秒';
                        $item['param']['type_name'] = '秒杀';
                        $newList = [];
                        foreach ($item['param']['list'] as &$value) {
                            $goodsList = [];
                            foreach ($value['goods_list'] as $k => $v) {
                                $startTime = strtotime($v['open_date'] . ' ' . $v['start_time'] . ':00:00');
                                if ($startTime >= time()) {
                                    $v['is_start'] = 0;
                                    $v['time'] = $startTime - time();
                                    $v['time_end'] = $startTime - time() + 3600;
                                    $v['time_content'] = $item['param']['list_style'] == 1 ? "活动未开始" : "距开始仅剩";
                                } else if ($startTime <= time() && $startTime + 3600 >= time()) {
                                    $v['is_start'] = 1;
                                    $v['time'] = $startTime - time() + 3600;
                                    $v['time_content'] = $item['param']['list_style'] == 1 ? "仅剩" : "距结束仅剩";
                                } else {
                                    $v['is_start'] = 1;
                                    $v['time'] = 0;
                                    $v['time_content'] = "已结束";
                                    continue;
                                }
                                $goodsList[] = $v;
                            }
                            if (count($goodsList) > 0) {
                                $value['goods_list'] = $goodsList;
                                $newList[] = $value;
                            }
                        }
                        $item['param']['list'] = $newList;
                        unset($value);
                        if (count($item['param']['list']) > 0) {
                            $timeAll['miaosha_' . $index] = $item;
                        }
                        break;
                    case 'pintuan':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : '去拼团';
                        $item['param']['type_name'] = '拼团';
                        if (count($item['param']['list']) > 0) {
                            $timeAll['pintuan_' . $index] = $item;
                        }
                        break;
                    case 'book':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : '预约';
                        break;
                    case 'bargain':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : '去参与';
                        $item['param']['type_name'] = '砍价';
                        $newList = [];
                        foreach ($item['param']['list'] as &$value) {
                            $goodsList = [];
                            foreach ($value['goods_list'] as $k => $v) {
                                $startTime = $v['begin_time'];
                                $endTime = $v['end_time'];
                                if ($startTime >= time()) {
                                    $v['is_start'] = 0;
                                    $v['time'] = $startTime - time();
                                    $v['time_end'] = $endTime - time();
                                    $v['time_content'] = $item['param']['list_style'] == 1 ? "活动未开始" : "距开始仅剩";
                                } else if ($startTime <= time() && $endTime >= time()) {
                                    $v['is_start'] = 1;
                                    $v['time'] = $endTime - time();
                                    $v['time_content'] = $item['param']['list_style'] == 1 ? "仅剩" : "距结束仅剩";
                                } else {
                                    $v['is_start'] = 1;
                                    $v['time'] = 0;
                                    $v['time_content'] = "已结束";
                                    continue;
                                }
                                $goodsList[] = $v;
                            }
                            if (count($goodsList) > 0) {
                                $value['goods_list'] = $goodsList;
                                $newList[] = $value;
                            }
                        }
                        $item['param']['list'] = $newList;
                        unset($value);
                        if (count($item['param']['list']) > 0) {
                            $timeAll['bargain_' . $index] = $item;
                        }
                        break;
                    case 'lottery':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : $item['param']['buy_default'];
                        $item['param']['type_name'] = '抽奖';
                        $newList = [];
                        foreach ($item['param']['list'] as &$value) {
                            $goodsList = [];
                            foreach ($value['goods_list'] as $k => $v) {
                                $startTime = $v['begin_time'];
                                $endTime = $v['end_time'];
                                if ($startTime >= time()) {
                                    $v['is_start'] = 0;
                                    $v['time'] = $startTime - time();
                                    $v['time_end'] = $endTime - time();
                                    $v['time_content'] = "距开始仅剩";
                                } else if ($startTime <= time() && $endTime >= time()) {
                                    $v['is_start'] = 1;
                                    $v['time'] = $endTime - time();
                                    $v['time_content'] = $item['param']['list_style'] == 1 ? "仅剩" : "距结束仅剩";
                                } else {
                                    $v['is_start'] = 1;
                                    $v['time'] = 0;
                                    $v['time_content'] = "已结束";
                                    continue;
                                }
                                $goodsList[] = $v;
                            }
                            if (count($goodsList) > 0) {
                                $value['goods_list'] = $goodsList;
                                $newList[] = $value;
                            }
                        }
                        $item['param']['list'] = $newList;
                        unset($value);
                        if (count($item['param']['list']) > 0) {
                            $timeAll['lottery_' . $index] = $item;
                        }
                        break;
                    case 'mch':
                        $param = &$item['param'];
                        $mchList = [];
                        foreach ($item['param']['list'] as $key => &$value) {
                            if ($param['is_goods']) {
                                if ($value['mch_id'] && $param['list'][$key]['goods_style'] != 2) {
                                    $res = DiyGoods::getGoods('goods', [], 0, 10, false, 1, $param['list'][$key]['mch_id']);
                                    $goods_list = $res['goods_list'];
                                    if ($param['list'][$key]['goods_style'] == 1) {
                                        array_splice($goods_list, $param['list'][$key]['goods_num']);
                                    }
                                    $value['goods_list'] = $goods_list;
                                }
                            }
                            $mchList[] = $value;
                        }
                        unset($value);
                        $item['param']['list'] = $mchList;
                        break;
                    case 'integral':
                        $item['param']['buy_content'] = $item['param']['buy_content'] ? $item['param']['buy_content'] : '立即兑换';
                        if ($item['param']['is_coupon'] == 1) {
                            $item['param']['coupon_list'] = $this->getIntegralCouponList();
                        }
                        break;
                    case 'float':
                        $store = [
                            'option' => [
                                'quick_map' => [
                                    'status' => $item['param']['quick_map_status'],
                                    'icon' => $item['param']['icon'],
                                    'address' => $item['param']['address'],
                                    'lal' => $item['param']['lal'],
                                ],
                                'web_service_status' => $item['param']['web_service_status'],
                                'web_service_url' => $item['param']['web_service_url'],
                                'web_service' => $item['param']['web_service'],
                                'wxapp' => [
                                    'status' => $item['param']['wxapp_status'],
                                    'appid' => $item['param']['appid'],
                                    'path' => $item['param']['path'],
                                    'pic_url' => $item['param']['pic_url'],
                                ]
                            ],
                            'dial' => $item['param']['dial'],
                            'dial_pic' => $item['param']['dial_pic'],
                            'contact_tel' => $item['param']['dial_tel'],
                            'show_customer_service' => $item['param']['show_customer_service'],
                            'service' => $item['param']['service'],
                        ];
                        $setnavi = [
                            'type' => $item['param']['cat_style'],
                            'home_img' => $item['param']['home_img'],
                        ];
                        $click_pic = [
                            'open' => $item['param']['open'],
                            'close' => $item['param']['close']
                        ];
                        $item['param']['store'] = $store;
                        $item['param']['setnavi'] = $setnavi;
                        $item['param']['click_pic'] = $click_pic;
                        break;
                    default:
                        break;
                }
            }
            unset($item);
            return [
                'code' => 0,
                'msg' => '请求成功',
                'data' => [
                    'template' => $detail['template'],
                    'act_modal_list' => $actModalList,
                    'info' => $page->title,
                    'page_id' => $page->id,
                    'status' => 'diy',
                    'time_all' => $timeAll
                ]
            ];
        }
    }

    public static function getTopic()
    {
        $topicList = self::$topicList;
        if ($topicList) {
            return $topicList;
        }
        $topicList = Topic::find()->where(['store_id' => \Yii::$app->store->id, 'is_delete' => 0])
            ->orderBy('sort ASC,addtime DESC')->limit(6)->select('id,title')->asArray()->all();
        self::$topicList = $topicList;
        return $topicList;
    }


    public static function getUrl($url)
    {
        preg_match('/^[^\?+]\?([\w|\W]+)=([\w|\W]*?)&([\w|\W]+)=([\w|\W]*?)$/', $url, $res);
        return $res;
    }

    private function getCoupon()
    {
        if ($this->couponList) {
            return $this->couponList;
        }
        $couponForm = new CouponListForm();
        $couponForm->store_id = $this->store->id;
        $couponForm->user_id = \Yii::$app->user->id;
        $this->couponList = $couponForm->getList();
        return $this->couponList;
    }

    private function getIntegralCouponList()
    {
        if ($this->integralCouponList) {
            return $this->integralCouponList;
        }
        $integralCouponList = Coupon::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'is_integral' => 2])
            ->orderBy('sort ASC')->asArray()->all();

        foreach ($integralCouponList as &$value) {
            $value['receive_content'] = "立即兑换";
        }
        $this->integralCouponList = $integralCouponList;
        return $integralCouponList;
    }
}