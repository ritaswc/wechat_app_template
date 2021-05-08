<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/8
 * Time: 14:01
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\modules\mch\models\data_export;


use app\hejiang\ApiCode;
use app\models\AppNavbar;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\Banner;
use app\models\Cat;
use app\models\Goods;
use app\models\HomeBlock;
use app\models\HomeNav;
use app\models\HomePageModule;
use app\models\IntegralCat;
use app\models\IntegralGoods;
use app\models\Level;
use app\models\Model;
use app\models\MsGoods;
use app\models\Option;
use app\models\PtCat;
use app\models\PtGoods;
use app\models\Store;
use app\models\User;
use app\models\Video;
use app\models\WechatApp;
use app\models\YyCat;
use app\models\YyGoods;

/**
 * Class ExportForm
 * @package app\modules\mch\models\data_export
 */
class ExportForm extends Model
{
    public $limit = 10000;
    public $page = 1;
    public $type; // 当前请求的数据键值
    public $store_id;
    public $token;
    protected $offset;

    public function rules()
    {
        return [
            [['page', 'limit', 'store_id'], 'integer'],
            [['type', 'token'], 'trim'],
            [['type', 'token'], 'string'],
        ];
    }

    protected function getOffset()
    {
        return ($this->page - 1) * $this->limit;
    }

    public function exportDownload()
    {
        set_time_limit(0);
        $t1 = microtime(true);
        $list = $this->search();
        foreach ($list as $key => &$value) {
            $getter = 'get' . $key;
            if (method_exists($this, $getter)) {
                $value = $this->$getter();
            }
        }
        unset($value);
        $string = json_encode($list, JSON_UNESCAPED_UNICODE);
        $t2 = microtime(true);
        \Yii::error('耗时'.round($t2-$t1,3).'秒');
        \Yii::error('Now memory_get_usage: ' . memory_get_usage());
        $this->download($string);
    }

    protected function search()
    {
        return [
            'wechat_app' => '',
            'banner' => '',
            'home_nav' => '',
            'home_block' => '',
            'navbar' => '',
            'block' => '',
            'user_center' => '',
            'store' => '',
            'member' => '',
            'user' => '',
            'cat' => '',
            'goods' => '',
            'ms_goods' => '',
            'pt_cat' => '',
            'pt_goods' => '',
            'yy_cat' => '',
            'yy_goods' => '',
            'integral_cat' => '',
            'integral_goods' => '',
        ];
    }

    private function download($string)
    {
        $handle = fopen('php://temp', 'rwb');
        fwrite($handle, $string);
        $name = date('YmdHis', time()) . rand(1000, 9999); //导出文件名称
        \Yii::$app->response->sendStreamAsFile($handle, 'v3data-' . $name . '.json');
    }

    protected function getWechat_app()
    {
        $wechatApp = WechatApp::find()->where(['id' => $this->store->wechat_app_id])->asArray()->one();
        return $wechatApp;
    }

    protected function getVideo()
    {
        $video = Video::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->asArray()->all();
        return $video;
    }

    protected function getBanner()
    {
        $banner = Banner::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->asArray()->all();
        return $banner;
    }

    protected function getHome_nav()
    {
        $homeNav = HomeNav::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->asArray()->all();
        return $homeNav;
    }

    protected function getHome_block()
    {
        $homeBlock = HomeBlock::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->asArray()->all();
        return $homeBlock;
    }

    protected function getNavbar()
    {
        $navbar = AppNavbar::getNavbar($this->store->id);
        $navigation = Option::get('navigation_bar_color', $this->store->id, 'app', [
            'frontColor' => '#000000',
            'backgroundColor' => '#ffffff',
            'bottomBackgroundColor' => '#ffffff',
        ]);
        return array_merge($navbar, (array)$navigation);
    }

    protected function getBlock()
    {
        $form = new HomePageModule();
        $form->store_id = $this->store->id;
        $selectModule = [
            'edit_list' => $this->store->home_page_module ? json_decode($this->store->home_page_module, true) : '',
            'update_list' => $form->search_1(),
            'notice' => Option::get('notice', $this->store->id, 'admin'),
        ];
        return $selectModule;
    }

    protected function getUser_center()
    {
        $data = Option::get('user_center_data', $this->store->id);
        if ($data) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    protected function getStore()
    {
        $setting = $this->store->toArray();

        $option = Option::getList([
            'service', 'notice', 'postage',
            'web_service', 'web_service_url', 'payment', 'wxapp', 'quick_navigation', 'quick_map',
            'phone_auth', 'good_negotiable', 'mobile_verify', 'web_service_status'
        ], $this->store->id, 'admin');
        $option['quick_map'] = $option['quick_map'] ? (array)$option['quick_map'] : '';

        return array_merge($setting, $option);
    }

    protected function getMember()
    {
        $member = Level::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->asArray()->all();
        return $member;
    }

    protected function getUser()
    {
        $user = User::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'type' => 1])
            ->with(['share' => function ($query) {
                $query->andWhere(['status' => 1, 'is_delete' => 0]);
            }])->limit($this->limit)->offset($this->getOffset())->asArray()->all();
        if (!$user || count($user) <= 0 || count($user) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        return $user;
    }

    protected function getCat()
    {
        $list = Cat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'parent_id' => 0])
            ->with(['childrenList' => function ($query) {
                $query->andWhere(['is_delete' => 0]);
            }])->asArray()->all();
        return $list;
    }

    protected function getGoods()
    {
        /* @var Goods[] $goods */
        $goods = Goods::find()->with(['goodsPicList', 'gc' => function ($query) {
            $query->andWhere(['is_delete' => 0]);
        }])
            ->where(['is_delete' => 0, 'store_id' => $this->store->id, 'mch_id' => 0])
            ->limit($this->limit)->offset($this->getOffset())
            ->asArray()->all();
        if (!$goods || count($goods) <= 0 || count($goods) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        foreach ($goods as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
            $this->setAttrId($item['attr']);
        }
        unset($item);
        $this->setAttrGroup();
        foreach ($goods as &$item) {
            $item['attr'] = $this->getAttrGroup($item['attr']);
        }
        return $goods;
    }

    /* @var AttrGroup[] $attr_group */
    public static $attr_group = [];
    public static $attr_id = [];
    public static $diff_attr_id = [];

    // 设置attr_id
    protected function setAttrId($attr)
    {
        foreach ($attr as $item) {
            foreach ($item['attr_list'] as $value) {
                if (isset($value['attr_id'])) {
                    if (in_array($value['attr_id'], self::$attr_id)) {
                        continue;
                    }
                    if (in_array($value['attr_id'], self::$diff_attr_id)) {
                        continue;
                    }
                    self::$diff_attr_id[] = $value['attr_id'];
                    continue;
                }
            }
        }
    }

    // 设置attr_group
    protected function setAttrGroup()
    {
        if (count(self::$diff_attr_id) > 0) {
            $attrQuery = Attr::find()->select('attr_group_id')->distinct()->where(['id' => self::$diff_attr_id]);
            $attrGroup = AttrGroup::find()->with('attr')->where(['id' => $attrQuery])->all();
            /* @var AttrGroup[] $attrGroup */
            foreach ($attrGroup as $item) {
                foreach ($item->attr as $attr) {
                    if (!in_array($attr->id, self::$attr_id) && !in_array($attr->id, self::$diff_attr_id)) {
                        self::$diff_attr_id[] = $attr->id;
                    }
                }
            }
            self::$attr_group = array_merge(self::$attr_group, $attrGroup);
            self::$attr_id = array_merge(self::$attr_id, self::$diff_attr_id);
            sort(self::$attr_id);
            self::$diff_attr_id = [];
        }
    }

    // 获取attr_group
    protected function getAttrGroup($param)
    {
        foreach ($param as &$item) {
            foreach ($item['attr_list'] as &$value) {
                foreach (self::$attr_group as $attrGroup) {
                    foreach ($attrGroup->attr as $attr) {
                        if ($attr->id == $value['attr_id']) {
                            $value['attr_group_id'] = $attrGroup->id;
                            $value['attr_group_name'] = $attrGroup->attr_group_name;
                        }
                    }
                }
                unset($value);
            }
        }
        unset($item);
        return $param;
    }

    protected function getMs_goods()
    {
        /* @var MsGoods[] $goods */
        $goods = MsGoods::find()->with(['goodsPicList'])
            ->where(['is_delete' => 0, 'store_id' => $this->store->id])
            ->limit($this->limit)->offset($this->getOffset())
            ->asArray()->all();
        if (!$goods || count($goods) <= 0 || count($goods) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        foreach ($goods as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
            $this->setAttrId($item['attr']);
        }
        unset($item);
        $this->setAttrGroup();
        foreach ($goods as &$item) {
            $item['attr'] = $this->getAttrGroup($item['attr']);
        }
        return $goods;
    }

    protected function getPt_goods()
    {
        /* @var PtGoods[] $goods */
        $goods = PtGoods::find()->with(['goodsPicList'])
            ->where(['is_delete' => 0, 'store_id' => $this->store->id])
            ->limit($this->limit)->offset($this->getOffset())
            ->asArray()->all();
        if (!$goods || count($goods) <= 0 || count($goods) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        foreach ($goods as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
            $this->setAttrId($item['attr']);
        }
        unset($item);
        $this->setAttrGroup();
        foreach ($goods as &$item) {
            $item['attr'] = $this->getAttrGroup($item['attr']);
        }
        return $goods;
    }

    protected function getPt_cat()
    {
        $list = PtCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->asArray()->all();
        return $list;
    }

    protected function getYy_goods()
    {
        /* @var YyGoods[] $goods */
        $goods = YyGoods::find()->with(['goodsPicList'])
            ->where(['is_delete' => 0, 'store_id' => $this->store->id])
            ->limit($this->limit)->offset($this->getOffset())
            ->asArray()->all();
        if (!$goods || count($goods) <= 0 || count($goods) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        foreach ($goods as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
            $this->setAttrId($item['attr']);
        }
        unset($item);
        $this->setAttrGroup();
        foreach ($goods as &$item) {
            $item['attr'] = $this->getAttrGroup($item['attr']);
        }
        return $goods;
    }

    protected function getYy_cat()
    {
        $list = YyCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->asArray()->all();
        return $list;
    }

    protected function getIntegral_goods()
    {
        /* @var IntegralGoods[] $goods */
        $goods = IntegralGoods::find()
            ->where(['is_delete' => 0, 'store_id' => $this->store->id])
            ->limit($this->limit)->offset($this->getOffset())
            ->asArray()->all();
        if (!$goods || count($goods) <= 0 || count($goods) < $this->limit) {
            $this->page = 1;
        } else {
            $this->page += 1;
        }
        foreach ($goods as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
            $this->setAttrId($item['attr']);
        }
        unset($item);
        $this->setAttrGroup();
        foreach ($goods as &$item) {
            $item['attr'] = $this->getAttrGroup($item['attr']);
        }
        return $goods;
    }

    protected function getIntegral_cat()
    {
        $list = IntegralCat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->asArray()->all();
        return $list;
    }

    protected function searchList()
    {
        return [
            'wechat_app',
            'banner',
            'home_nav',
            'home_block',
            'navbar',
            'block',
            'user_center',
            'store',
            'member',
            'user',
            'cat',
            'goods',
            'ms_goods',
            'pt_cat',
            'pt_goods',
            'yy_cat',
            'yy_goods',
            'integral_cat',
            'integral_goods',
        ];
    }

    public function export()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $this->offset = $this->getOffset();
            $this->store = Store::findOne($this->store_id);
            if (!$this->store) {
                throw new \Exception('查询不到商城，请使用正确的商城数据码');
            }
            $token = Option::get('export_token', $this->store->id, 'mall');
            if ($this->token !== $token) {
                throw new \Exception('商城数据码，已过期');
            }
            set_time_limit(0);
            $t1 = microtime(true);
            $list = $this->searchList();
            $result = [];
            $type = $this->type;
            $count = 0;
            foreach ($list as $key => $value) {
                if ($type) {
                    if ($type != $value) {
                        continue;
                    }
                }
                $getter = 'get' . $value;
                if (method_exists($this, $getter)) {
                    $newItem = $this->$getter();
                    $result[$value] = $newItem;
                    $count += count($newItem);
                    if ($count >= $this->limit) {
                        if ($this->page <= 1) {
                            $index = count($list) > $key ? $key + 1 : count($list);
                            $type = $list[$index];
                        }
                        break;
                    } else {
                        $index = count($list) > $key ? $key + 1 : count($list);
                        $type = $list[$index];
                    }
                }
            }
            $t2 = microtime(true);
            \Yii::error('耗时'.round($t2-$t1,3).'秒');
            \Yii::error('Now memory_get_usage: ' . memory_get_usage());
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => [
                    'result' => $result,
                    'type' => $type,
                    'page' => $this->page
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
