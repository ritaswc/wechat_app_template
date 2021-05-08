<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:43
 */

namespace app\modules\mch\controllers;

use app\hejiang\ApiCode;
use app\hejiang\cloud\CloudWxappUpload;
use app\models\common\admin\store\CommonAppDisabled;
use app\models\common\admin\store\CommonStoreUpload;
use app\models\common\CommonDistrict;
use app\modules\mch\models\PickLinkForm;
use app\modules\mch\models\WxForm;
use app\utils\FileHelper;
use app\models\AppNavbar;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\Banner;
use app\models\Cat;
use app\models\Delivery;
use app\models\DistrictArr;
use app\models\Express;
use app\models\Form;
use app\models\FreeDeliveryRules;
use app\models\HomeBlock;
use app\models\HomeNav;
use app\models\HomePageModule;
use app\models\MailSetting;
use app\models\Option;
use app\models\Order;
use app\models\PostageRules;
use app\models\Sender;
use app\models\Shop;
use app\models\SmsSetting;
use app\models\TerritorialLimitation;
use app\models\UploadConfig;
use app\models\UploadFile;
use app\models\User;
use app\models\UserCenterForm;
use app\models\UserCenterMenu;
use app\models\Video;
use app\modules\mch\models\AttrAddForm;
use app\modules\mch\models\AttrDeleteForm;
use app\modules\mch\models\AttrUpdateForm;
use app\modules\mch\models\BannerForm;
use app\modules\mch\models\CatForm;
use app\modules\mch\models\DeliveryForm;
use app\modules\mch\models\FreeDeliveryRulesEditForm;
use app\modules\mch\models\HomeBlockEditForm;
use app\modules\mch\models\HomeNavEditForm;
use app\modules\mch\models\MailForm;
use app\modules\mch\models\NavbarEditForm;
use app\modules\mch\models\OfferPriceForm;
use app\modules\mch\models\OrderMessageForm;
use app\modules\mch\models\PostageRulesEditForm;
use app\modules\mch\models\SenderForm;
use app\modules\mch\models\ShopForm;
use app\modules\mch\models\SmsForm;
use app\modules\mch\models\StoreDataForm;
use app\modules\mch\models\StoreSettingForm;
use app\modules\mch\models\SubmitFormForm;
use app\modules\mch\models\TerritorialLimitationForm;
use app\modules\mch\models\VideoForm;
use app\modules\mch\models\WxdevToolLoginForm;
use app\modules\mch\models\WxdevToolPreviewForm;
use app\modules\mch\models\WxdevToolUploadForm;
use Comodojo\Zip\Zip;
use app\modules\mch\models\StoreStatsForm;

class StoreController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new StoreDataForm();
            $form->store_id = $this->store->id;
            $form->sign = \Yii::$app->request->get('sign');
            $form->type = \Yii::$app->request->get('type');
            $store_data = $form->search();
            return $store_data;
        } else {
            return $this->render('index', [
                'store' => $this->store,
                'plug' => parent::getAllPermission()
            ]);
        }
    }

    /**
     * 插件统计
     *
     */
    public function actionStats()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new StoreStatsForm();
            $form->store_id = $this->store->id;
            $form->sign = \Yii::$app->request->get('sign');
            $form->type = \Yii::$app->request->get('type');
            $form->name = \Yii::$app->request->get('name');
            $store_data = $form->search();
            return $store_data;
        } else {
            return $this->render('index', [
                'store' => $this->store,
            ]);
        }
    }


    /**
     *  小程序数据分析
     */

    public function actionAnalytics()
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $new_time = $post['time'];
            $now_time = time();
            $new_day = [
                'begin_date' => date('Ymd', strtotime($new_time)),
                'end_date' => date('Ymd', strtotime($new_time)),
            ];
            $last_day = [
                'begin_date' => date('Ymd', strtotime("-2 day", $now_time)),
                'end_date' => date('Ymd', strtotime("-2 day", $now_time)),
            ];
            $data = [
                'visitdistribution' => $this->checkAnalysis(7, $new_day),
                'lastvisitdistribution' => $this->checkAnalysis(7, $last_day),
            ];
            return [
                'code' => 0,
                'data' => $data,
            ];
        }

        $timestamp = time();
        //day
        $day = [
            'begin_date' => date('Ymd', strtotime("-1 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-1 day", $timestamp)),
        ];
        $lastday = [
            'begin_date' => date('Ymd', strtotime("-2 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-2 day", $timestamp)),
        ];

        //week
        $week = [
            'begin_date' => date('Ymd', strtotime("last week Monday", $timestamp)),
            'end_date' => date('Ymd', strtotime("last week Sunday", $timestamp)),
        ];
        $lastweek = [
            'begin_date' => date('Ymd', strtotime("last week Monday -7 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("last week Sunday -7 day", $timestamp)),
        ];
        //month
        $last_month = date('Y-m-01', strtotime("last month", $timestamp));
        $month = [
            'begin_date' => date('Ymd', strtotime($last_month)),
            'end_date' => date('Ymd', strtotime("$last_month +1 month -1 seconds")),
        ];
        $last_last_month = date('Y-m-01', strtotime("last month -1 month", $timestamp));

        $lastmonth = [
            'begin_date' => date('Ymd', strtotime($last_last_month)),
            'end_date' => date('Ymd', strtotime("$last_last_month +1 month -1 seconds")),
        ];


        $data = [
            'dailyretaininfo' => $this->checkAnalysis(0, $day),
            'lastdailyretaininfo' => $this->checkAnalysis(0, $lastday),
            'weeklyretaininfo' => $this->checkAnalysis(1, $week),
            'lastweeklyretaininfo' => $this->checkAnalysis(1, $lastweek),
            'monthlyretaininfo' => $this->checkAnalysis(2, $month),
            'lastmonthlyretaininfo' => $this->checkAnalysis(2, $lastmonth),
            'dailyvisittrend' => $this->checkAnalysis(3, $day),
            'lastdailyvisittrend' => $this->checkAnalysis(3, $lastday),
            'weeklyvisittrend' => $this->checkAnalysis(4, $week),
            'lastweeklyvisittrend' => $this->checkAnalysis(4, $lastweek),
            'monthlyvisittrend' => $this->checkAnalysis(5, $month),
            'lastmonthlyvisittrend' => $this->checkAnalysis(5, $lastmonth),

            // 'userportraitone'=>$this->checkAnalysis(6,$day),
            // 'userportraitseven'=>$this->checkAnalysis(6,$sevenDay),
            // 'userportraitthirty'=>$this->checkAnalysis(6,$thirtyDay),
            // 'visitdistribution'=>$this->checkAnalysis(7,$day),

            'dailysummarytrend' => $this->checkAnalysis(8, $day),
            'lastsummarytrend' => $this->checkAnalysis(8, $lastday),

            // 'thirdretaininfo' => $this->checkAnalysis(0,$thirdDay),
            // 'thirdvisittrend' => $this->checkAnalysis(3,$thirdDay),
            // 'fourthretaininfo' => $this->checkAnalysis(0,$fourthDay),
            // 'fourthvisittrend' => $this->checkAnalysis(3,$fourthDay),
            // 'fifthretaininfo' => $this->checkAnalysis(0,$fifthDay),
            // 'fifthvisittrend' => $this->checkAnalysis(3,$fifthDay),
            // 'sixthretaininfo' => $this->checkAnalysis(0,$sixthDay),
            // 'sixthvisittrend' => $this->checkAnalysis(3,$sixthDay),
            // 'seventhretaininfo' => $this->checkAnalysis(0,$seventhDay),
            // 'seventhvisittrend' => $this->checkAnalysis(3,$seventhDay),
        ];
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    public function actionAnalyticsOne()
    {
        $timestamp = time();
        $day = [
            'begin_date' => date('Ymd', strtotime("-1 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-1 day", $timestamp)),
        ];
        $sevenDay = [
            'begin_date' => date('Ymd', strtotime("-7 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-1 day", $timestamp)),
        ];

        $thirtyDay = [
            'begin_date' => date('Ymd', strtotime("-30 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-1 day", $timestamp)),
        ];


        $data = [
            'userportraitone' => $this->checkAnalysis(6, $day),
            'userportraitseven' => $this->checkAnalysis(6, $sevenDay),
            'userportraitthirty' => $this->checkAnalysis(6, $thirtyDay),
            'visitdistribution' => $this->checkAnalysis(7, $day),
        ];
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    public function actionAnalyticsTwo()
    {
        $timestamp = time();
        //day
        $thirdDay = [
            'begin_date' => date('Ymd', strtotime("-3 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-3 day", $timestamp)),
        ];
        $fourthDay = [
            'begin_date' => date('Ymd', strtotime("-4 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-4 day", $timestamp)),
        ];
        $fifthDay = [
            'begin_date' => date('Ymd', strtotime("-5 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-5 day", $timestamp)),
        ];
        $sixthDay = [
            'begin_date' => date('Ymd', strtotime("-6 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-6 day", $timestamp)),
        ];
        $seventhDay = [
            'begin_date' => date('Ymd', strtotime("-7 day", $timestamp)),
            'end_date' => date('Ymd', strtotime("-7 day", $timestamp)),
        ];

        $data = [
            'thirdretaininfo' => $this->checkAnalysis(0, $thirdDay),
            'thirdvisittrend' => $this->checkAnalysis(3, $thirdDay),
            'fourthretaininfo' => $this->checkAnalysis(0, $fourthDay),
            'fourthvisittrend' => $this->checkAnalysis(3, $fourthDay),
            'fifthretaininfo' => $this->checkAnalysis(0, $fifthDay),
            'fifthvisittrend' => $this->checkAnalysis(3, $fifthDay),
            'sixthretaininfo' => $this->checkAnalysis(0, $sixthDay),
            'sixthvisittrend' => $this->checkAnalysis(3, $sixthDay),
            'seventhretaininfo' => $this->checkAnalysis(0, $seventhDay),
            'seventhvisittrend' => $this->checkAnalysis(3, $seventhDay),
        ];
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    private function checkAnalysis($type, $time)
    {
        $access_token = $this->wechat->getAccessToken();
        $api = [
            "https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo?access_token={$access_token}", //日留存 type=0
            "https://api.weixin.qq.com/datacube/getweanalysisappidweeklyretaininfo?access_token={$access_token}", //周留存  type=1
            "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo?access_token={$access_token}", //月留存 type=2
            "https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token={$access_token}", //访问日趋势
            "https://api.weixin.qq.com/datacube/getweanalysisappidweeklyvisittrend?access_token={$access_token}", //访问周趋势
            "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyvisittrend?access_token={$access_token}",//访问月趋势
            "https://api.weixin.qq.com/datacube/getweanalysisappiduserportrait?access_token={$access_token}", //活跃画像
            "https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage?access_token={$access_token}", //页面

            "https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token={$access_token}", //访问概况
            // "https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution?access_token={$access_token}",//访问分布
        ];
        $api = $api[$type];

        $data = json_encode($time, JSON_UNESCAPED_UNICODE);
        $this->wechat->curl->post($api, $data);
        $res = json_decode($this->wechat->curl->response, true);
        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            return $res;
        } else {
            return $res;
        }
    }

    /**
     * 基本信息
     */
    public function actionSetting()
    {
        if (\Yii::$app->request->isPost) {
            $form = new StoreSettingForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->save();
        } else {
            $store = $this->store;
            foreach ($store as $index => $value) {
                if (in_array($index, ['home_page_module'])) {
                    continue;
                }
                $store[$index] = str_replace("\"", "&quot;", $value);
            }
            $option = Option::getList([
                'service', 'notice', 'postage',
                'web_service', 'web_service_url', 'payment', 'wxapp', 'quick_navigation', 'quick_map',
                'phone_auth', 'good_negotiable', 'mobile_verify', 'web_service_status'
            ], $this->store->id, 'admin');

            $option['quick_navigation'] = $option['quick_navigation'] ? \Yii::$app->serializer->decode($option['quick_navigation']) : [];
            $option['good_negotiable'] = $option['good_negotiable'] ? \Yii::$app->serializer->decode($option['good_negotiable']) : [];

            if ($option['mobile_verify'] === null) {
                $option['mobile_verify'] = 1;
            }

            if (!$option['payment']) {
                $option['payment']['wechat'] = 1;
            } else {
                $option['payment'] = \Yii::$app->serializer->decode($option['payment']);
                if (!$option['payment']) {
                    $option['payment']['wechat'] = 1;
                }
            }

            return $this->render('setting', [
                'store' => $store,
                'option' => $option,
            ]);
        }
    }

    /**
     * 首页幻灯片
     */
    public function actionSlide()
    {
        $store = $this->store->id;
        $form = new BannerForm();
        $form->type = 1;
        $arr = $form->getList($store);
        return $this->render('slide', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    /**
     * 幻灯片添加修改
     */
    public function actionSlideEdit($id = 0)
    {
        $banner = Banner::findOne(['id' => $id]);
        if (!$banner) {
            $banner = new Banner();
        }
        $form = new BannerForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->banner = $banner;
            return $form->save();
        }
        foreach ($banner as $index => $value) {
            $banner[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('slide-edit', [
            'list' => $banner,
        ]);
    }

    /**
     * 幻灯片删除
     * @param int $id
     */
    public function actionSlideDel($id = 0)
    {
        $dishes = Banner::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$dishes) {
            return [
                'code' => 1,
                'msg' => '幻灯片不存在或已经删除',
            ];
        }
        $dishes->is_delete = 1;
        if ($dishes->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($dishes->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    /**
     * 分类列表
     */
    public function actionCat()
    {
        $cat_list = Cat::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort,addtime DESC')->all();
        return $this->render('cat', [
            'cat_list' => $cat_list,
        ]);
    }

    /**
     * 分类编辑
     */
    public function actionCatEdit($id = null)
    {
        $cat = Cat::findOne(['id' => $id]);
        if (!$cat) {
            $cat = new Cat();
        }
        $form = new CatForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->cat = $cat;
            return $form->save();
        }
        $parent_list_query = Cat::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'parent_id' => 0,
        ]);
        if (!$cat->isNewRecord && $cat->parent_id == 0) {
            $parent_list_query->andWhere([
                'id' => -1,
            ]);
        }
        $parent_list = $parent_list_query->all();
        foreach ($cat as $index => $value) {
            $cat[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('cat-edit', [
            'parent_list' => $parent_list,
            'list' => $cat,
        ]);
    }

    /**
     * 分类删除
     */
    public function actionCatDel($id)
    {
        $dishes = Cat::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$dishes) {
            return [
                'code' => 1,
                'msg' => '商品分类删除失败或已删除',
            ];
        }
        $dishes->is_delete = 1;
        if ($dishes->save()) {
            Cat::updateAll(['is_delete' => 1], ['parent_id' => $id]);
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($dishes->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    /**
     * 运费规则列表
     */
    public function actionPostageRules()
    {
        return $this->render('postage-rules', [
            'list' => PostageRules::findAll([
                'store_id' => $this->store->id,
                'is_delete' => 0,
            ]),
        ]);
    }

    /**
     * 新增、编辑运费规则
     */
    public function actionPostageRulesEdit($id = null)
    {
        $model = PostageRules::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new PostageRules();
            $model->store_id = $this->store->id;
        }
        if (\Yii::$app->request->isPost) {
            $form = new PostageRulesEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->postage_rules = $model;
            return $form->save();
        } else {
            $province = DistrictArr::getRules();

            return $this->render('postage-rules-edit', [
                'model' => $model,
                'express_list' => Express::findAll([
                    'is_delete' => 0,
                ]),
                'province_list' => $province,
            ]);
        }
    }

    /**
     * 删除运费规则
     */
    public function actionPostageRulesDelete($id)
    {
        $model = PostageRules::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }

    /**
     * 包邮规则
     * @return [type] [description]
     */
    public function actionFreeDeliveryRules()
    {
        return $this->render('free-delivery-rules', [
            'list' => FreeDeliveryRules::findAll([
                'store_id' => $this->store->id,
            ]),
        ]);
    }

    /**
     * 新增、编辑包邮规则
     */
    public function actionFreeDeliveryRulesEdit($id = null)
    {
        $model = FreeDeliveryRules::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);

        if (!$model) {
            $model = new FreeDeliveryRules();
            $model->store_id = $this->store->id;
        }

        $list = FreeDeliveryRules::find()->where(['not', ['id' => $id]])->andWhere(['store_id' => $this->store->id])->asArray()->all();
        $city_list = array();

        for ($i = 0; $i < count($list); $i++) {
            $city = json_decode($list[$i]['city'], true);
            for ($i1 = 0; $i1 < count($city); $i1++) {
                $city_list[] = $city[$i1]['id'];
            }
        }

        if (\Yii::$app->request->isPost) {
            $form = new FreeDeliveryRulesEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->free_delivery_rules = $model;
            if (is_array($form->attributes['city'])) {
                foreach ($form->attributes['city'] as $item) {
                    if (in_array($item['id'], $city_list)) {
                        return [
                            'code' => 1,
                            'msg' => '参数错误',
                        ];
                    }
                }
            }
            return $form->save();
        } else {
            $province = DistrictArr::getRules();

            foreach ($province as $k => $v) {
                foreach ($v['city'] as $k1 => $v1) {
                    if (in_array($v1['id'], $city_list)) {
                        unset($province[$k]['city'][$k1]);
                    }
                }
                if ($province[$k]['city'] == array()) {
                    unset($province[$k]);
                }
            };

            return $this->render('free-delivery-rules-edit', [
                'model' => $model,
                'list' => $list,
                'province_list' => $province,
            ]);
        }
    }

    /**
     * 删除包邮规则
     */
    public function actionFreeDeliveryRulesDelete($id)
    {
        $model = FreeDeliveryRules::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        $model->delete();
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }

    public function actionPostageRulesSetEnable($id, $type)
    {
        if ($type == 0) {
            PostageRules::updateAll(['is_enable' => 0], ['store_id' => $this->store->id, 'is_delete' => 0, 'id' => $id]);
        }
        if ($type == 1) {
            PostageRules::updateAll(['is_enable' => 0], ['store_id' => $this->store->id, 'is_delete' => 0]);
            PostageRules::updateAll(['is_enable' => 1], ['store_id' => $this->store->id, 'is_delete' => 0, 'id' => $id]);
        }

        return [
            'code' => 0,
            'msg' => '更新成功'
        ];
    }

    //规格列表
    public function actionAttr()
    {
        $attr_list = Attr::find()
            ->select('a.id,ag.attr_group_name,a.attr_name')
            ->alias('a')->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
            ->where(['ag.store_id' => $this->store->id, 'a.is_delete' => 0, 'ag.is_delete' => 0, 'a.is_default' => 0])
            ->orderBy('ag.id DESC,a.id DESC')
            ->asArray()->all();
        $attr_query = Attr::find()->where(['is_delete' => 0])->groupBy('attr_group_id');
        return $this->render('attr', [
            'attr_group_list' => AttrGroup::find()->select('ag.*')->alias('ag')->leftJoin([
                'a' => $attr_query,
            ], 'a.attr_group_id=ag.id')->where(['ag.is_delete' => 0, 'ag.store_id' => $this->store->id, 'a.is_delete' => 0])->all(),
            'attr_list' => $attr_list,
        ]);
    }

    //添加规格
    public function actionAttrAdd()
    {
        if (\Yii::$app->request->isPost) {
            $form = new AttrAddForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->save();
        }
    }

    //修改规格
    public function actionAttrUpdate()
    {
        if (\Yii::$app->request->isPost) {
            $form = new AttrUpdateForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->save();
        }
    }

    //修改规格
    public function actionAttrDelete()
    {
        $form = new AttrDeleteForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->save();
    }

    //小程序发布
    public function actionWxapp($branch = null)
    {
        if (\Yii::$app->request->isPost) {
            $action = \Yii::$app->request->post('action');
            $jump_appid_list = Option::get('jump_appid_list', $this->store->id, '', []);
            if ($action == 'wxdev_tool_login') {
                return CloudWxappUpload::login([
                    'store_id' => $this->store->id,
                    'appid' => $this->wechat->appId,
                    'branch' => $branch,
                    'jump_appid_list' => $jump_appid_list,
                ]);
            } elseif ($action == 'wxdev_tool_preview') {
                return CloudWxappUpload::preview([
                    'store_id' => $this->store->id,
                    'appid' => $this->wechat->appId,
                    'branch' => $branch,
                    'jump_appid_list' => $jump_appid_list,
                ]);
            } elseif ($action == 'wxdev_tool_upload') {
                return CloudWxappUpload::upload([
                    'store_id' => $this->store->id,
                    'appid' => $this->wechat->appId,
                    'branch' => $branch,
                    'jump_appid_list' => $jump_appid_list,
                ]);
            }
        } else {
            return $this->render('wxapp', [
                'branch' => $branch,
                'jump_appid_list' => Option::get('jump_appid_list', $this->store->id, '', []),
            ]);
        }
    }

    //小程序发布（无多商户）
    public function actionWxappNomch($branch = 'nomch')
    {
        return $this->actionWxapp($branch);
    }

    // 配置微信小程序跳转appid列表
    public function actionJumpAppid()
    {
        if (\Yii::$app->request->isPost) {
            $jump_appid_list = \Yii::$app->request->post('jump_appid_list', []);
            $new_list = [];
            foreach ($jump_appid_list as $item) {
                $item = trim($item);
                if (!$item || !is_string($item) || mb_strlen($item) > 32) {
                    continue;
                }
                if (count($new_list) >= 10) {
                    break;
                }
                $new_list[] = $item;
            }
            Option::set('jump_appid_list', $new_list, $this->store->id);
            return [
                'code' => 0,
                'msg' => '保存成功。',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '非post请求。',
            ];
        }
    }

    //获取小程序二维码
    public function actionWxappQrcode()
    {
        if (\Yii::$app->request->isPost) {
            $save_file = md5($this->wechat->appId . $this->wechat->appSecret) . '.png';
            $save_dir = \Yii::$app->basePath . '/web/temp/' . $save_file;
            $web_dir = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $save_file;
            if (file_exists($save_dir)) {
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => $web_dir,
                ];
            }
            $access_token = $this->wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
            $data = \Yii::$app->serializer->encode([
                'scene' => '0',
                'path' => '/pages/index/index',
                'width' => 480,
            ]);
            $this->wechat->curl->post($api, $data);
            if (in_array('Content-Type: image/jpeg', $this->wechat->curl->response_headers)) {
                FileHelper::filePutContents($save_dir, $this->wechat->curl->response);
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => $web_dir,
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '获取小程序码失败',
                ];
            }
        } else {
            return [
                'code' => 1,
            ];
        }
    }

    //首页导航图标
    public function actionHomeNav()
    {
        $list = HomeNav::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('sort ASC,addtime DESC')->all();
        return $this->render('home-nav', [
            'list' => $list,
        ]);
    }

    /**
     * 首页导航图标编辑
     */
    public function actionHomeNavEdit($id = null)
    {
        $model = HomeNav::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$model) {
            $model = new HomeNav();
        }
        if (\Yii::$app->request->isPost) {
            $form = new HomeNavEditForm();
            $form->attributes = \Yii::$app->request->post('model');
            $form->store_id = $this->store->id;
            $form->model = $model;
            return $form->save();
        }
        return $this->render('home-nav-edit', [
            'model' => $model,
        ]);
    }

    //修改
    public function actionHomeNavStatus()
    {
        $post = \Yii::$app->request->post();
        $form = HomeNav::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'id' => $post['id']
        ]);
        if (!empty($form)) {
            if ($post['status'] !== null) {
                $form->is_hide = $post['status'];
            }
            if ($form->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return (new Model())->getErrorResponse($form);
            }
        }
    }

    /**
     * 首页导航图标删除
     */
    public function actionHomeNavDel($id = null)
    {
        $model = HomeNav::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '导航图标不存在，或已删除',
            ];
        }
        $model->is_delete = 1;
        $model->save();
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }

    /**
     * @return string
     * 短信模板设置
     */
    public function actionSms()
    {
        $form = new SmsForm();
        $list = SmsSetting::findOne(['store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$list) {
            $list = new SmsSetting();
        }
        if (\Yii::$app->request->isPost) {
            $form->store_id = $this->store->id;
            $form->sms = $list;
            $post = \Yii::$app->request->post();
            if ($post['status'] == 1) {
                $form->scenario = 'SUCCESS';
            }
            $form->attributes = $post;
            return $form->save();
        }
        $refund = [];
        if ($list->tpl_refund) {
            $refund = json_decode($list->tpl_refund, true);
        }
        $code = [];
        if ($list->tpl_code) {
            $code = json_decode($list->tpl_code, true);
        }
        foreach ($list as $index => $value) {
            if (in_array($index, ['tpl_refund', 'tpl_code'])) {
                continue;
            }
            $list[$index] = str_replace("\"", "&quot;", $value);
        }
        foreach ($refund as $index => $value) {
            $refund[$index] = str_replace("\"", "&quot;", $value);
        }
        foreach ($code as $index => $value) {
            $code[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('sms', [
            'sms' => $list,
            'refund' => $refund,
            'code' => $code,
        ]);
    }

    //首页设置
    public function actionHomePage()
    {
        if (\Yii::$app->request->isPost) {
            $this->store->home_page_module = \Yii::$app->request->post('module_list');
            if ($this->store->save()) {
                $update_list = \Yii::$app->request->post('update_list');
                Option::set('home_page_data', $update_list, $this->store->id, 'app');
                $notice = trim(\Yii::$app->request->post('notice'));
                Option::set('notice', $notice, $this->store->id, 'admin');
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '保存失败',
                ];
            }
        } else {
            $form = new HomePageModule();
            $form->store_id = $this->store->id;
            $form->userAuth = $this->getUserAuth();
            return $this->render('home-page', [
                'module_list' => $form->search(), //所有可选自定义板块
                'edit_list' => $form->search(1), //已选的板块
                'update_list' => $form->search_1(), //板块属性
                'notice' => Option::get('notice', $this->store->id, 'admin'),
            ]);
        }
    }

    //首页自定义板块
    public function actionHomeBlock()
    {
        $list = HomeBlock::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('addtime DESC')->all();
        return $this->render('home-block', [
            'list' => $list,
        ]);
    }

    //首页自定义板块编辑
    public function actionHomeBlockEdit($id = null)
    {
        $model = HomeBlock::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new HomeBlock();
        }
        if (\Yii::$app->request->isPost) {
            $form = new HomeBlockEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store = $this->store;
            return $form->save();
        } else {
            return $this->render('home-block-edit', [
                'model' => $model,
            ]);
        }
    }

    //首页自定义板块删除
    public function actionHomeBlockDelete($id = null)
    {
        $model = HomeBlock::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }

    //上传设置
    public function actionUpload()
    {
        $this->checkIsAdmin();
        $model = UploadConfig::findOne([
            'store_id' => 0,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new UploadConfig();
        }
        if (\Yii::$app->request->isPost) {
            $common = new CommonStoreUpload();
            $common->attributes = \Yii::$app->request->post();
            $common->model = $model;
            $common->store_id = $this->store->id;
            return $common->save();
        } else {
            $model->aliyun = json_decode($model->aliyun, true);
            $model->qcloud = json_decode($model->qcloud, true);
            $model->qiniu = json_decode($model->qiniu, true);
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    public function actionUploadTest()
    {
        return $this->render('upload-test');
    }

    /**
     * @return string
     * 面单设置列表
     */
    public function actionExpress()
    {
        $form = new DeliveryForm();
        $form->store_id = $this->store->id;
        $arr = $form->getList();
        $commonDistrict = new CommonDistrict();
        $province_list = $commonDistrict->search();
        $sender = Sender::findOne(['store_id' => $this->store->id, 'is_delete' => 0, 'delivery_id' => 0]);
        if (!$sender) {
            $sender = new Sender();
        }
        if (\Yii::$app->request->isPost) {
            $sender_form = new SenderForm();
            $sender_form->store_id = $this->store->id;
            $sender_form->delivery_id = 0;
            $sender_form->sender = $sender;
            $sender_form->attributes = \Yii::$app->request->post('model');
            return $sender_form->save();
        }
        foreach ($sender as $index => $value) {
            $sender[$index] = str_replace("\"", "&quot;", $value);
        }
        foreach ($arr[0] as $index => $value) {
            $arr[0][$index] = str_replace("\"", "&quot;", $value);
        }

        return $this->render('express', [
            'list' => $arr[0],
            'pagination' => $arr[1],
            'district' => \Yii::$app->serializer->encode($province_list),
            'sender' => $sender,
        ]);
    }

    /**
     * @return string
     * 面单配置
     */
    public function actionExpressEdit($id = null)
    {
        $expressCode = 'SF';
        $express = Express::getExpressList();
        $list = Delivery::findOne(['id' => $id]);
        if (!$list) {
            $list = new Delivery();
        } else {
            foreach ($express as $item) {
                if ($item['id'] == $list['express_id']) {
                    $expressCode = $item['code'];
                    break;
                }
            }
        }
        $commonDistrict = new CommonDistrict();
        $province_list = $commonDistrict->search();
        $sender = Sender::findOne(['store_id' => $this->store->id, 'is_delete' => 0, 'delivery_id' => $list['id']]);
        if (!$sender) {
            $sender = new Sender();
        }
        if (\Yii::$app->request->isPost) {
            $t = \Yii::$app->db->beginTransaction();
            $form = new DeliveryForm();
            $form->store_id = $this->store->id;
            $form->delivery = $list;
            $form->attributes = \Yii::$app->request->post('model');
            $res = $form->save();
            if ($res['code'] == 1) {
                return $res;
            }
            $sender_form = new SenderForm();
            $sender_form->store_id = $this->store->id;
            $sender_form->delivery_id = $form->delivery->id;
            $sender_form->sender = $sender;
            $sender_form->attributes = \Yii::$app->request->post('model');
            $res1 = $sender_form->save();
            if ($res1['code'] == 1) {
                return $res1;
            }
            if ($res['code'] == 0 && $res1['code'] == 0) {
                $t->commit();
                return $res;
            } else {
                $t->rollBack();
                return ['code' => 1, 'msg' => '网络异常'];
            }
            return $sender_form->save();
        }

        return $this->render('express-edit', [
            'express' => $express,
            'list' => $list,
            'district' => \Yii::$app->serializer->encode($province_list),
            'sender' => $sender,
            'express_code' => $expressCode,
            'template_size_list' => \Yii::$app->serializer->encode(Express::getTemplateSize())
        ]);
    }

    /**
     * @param int $id
     * @return mixed|string
     * @throws \yii\db\Exception
     * 删除
     */
    public function actionExpressDel($id = 0)
    {
        $list = Delivery::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$list) {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
        $list->is_delete = 1;
        if ($list->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    public function actionUploadFileList($type = 'image', $page = 1, $dataType = 'json', $group_id = -1)
    {
        $offset = ($page - 1) * 20;
        $query = UploadFile::find()
            ->where(['store_id' => $this->store->id, 'is_delete' => 0, 'type' => $type]);

        if ($group_id > 0) {
            $query->andWhere(['group_id' => $group_id]);
        }
        if ($group_id == 0) {
            $query->andWhere(['or', ['group_id' => 0], ['group_id' => null]]);
        }

        $list = $query->orderBy('addtime DESC')
            ->limit(20)->offset($offset)->asArray()->select('file_url,id')->all();
        foreach ($list as $index => $value) {
            $list[$index]['selected'] = 0;
        }
        if ($dataType == 'json') {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $list,
                ],
            ];
        }
        if ($dataType == 'html') {
            $this->layout = false;
            return $this->render('upload-file-list', [
                'list' => $list,
            ]);
        }
    }

    public function actionVideo()
    {
        $store_id = $this->store->id;
        $form = new VideoForm();
        $arr = $form->getList($store_id);
        return $this->render('video', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    public function actionVideoEdit($id = null)
    {
        $video = Video::findOne(['id' => $id]);
        if (!$video) {
            $video = new Video();
        }

        $form = new VideoForm();
        if (\Yii::$app->request->isPost) {
            $form->video = $video;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post('model');
            return $form->save();
        }
        foreach ($video as $index => $value) {
            $video[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('video-edit', [
            'list' => $video,
        ]);
    }

    public function actionVideoDel($id = null)
    {
        $video = Video::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$video) {
            return [
                'code' => 1,
                'msg' => '不存在或已经删除',
            ];
        }
        $video->is_delete = 1;
        if ($video->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($video->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    public function actionShop()
    {
        $form = new ShopForm();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        $form->limit = 20;
        $arr = $form->getList();

//        $shop = array();
//        foreach ($arr['list'] as $k => $v) {
//            $shop[] = $v['id'];
//        };
//        $detail = Order::find()->where(['in', 'shop_id', $shop])->andwhere(['is_pay' => 1, 'store_id' => $this->store->id])->asArray()->all();
//
//        foreach ($arr['list'] as $k => $v) {
//            $pay_price = 0;
//            foreach ($detail as $k1 => $v1) {
//                if ($v1['shop_id'] == $v['id']) {
//                    $pay_price += $v1['pay_price'];
//                }
//            }
//            $arr['list'][$k]['total_price'] = $pay_price;
//        }

        return $this->render('shop', [
            'row_count' => $arr['row_count'],
            'pagination' => $arr['pagination'],
            'list' => $arr['list'],
        ]);
    }

    public function actionShopDel($id = null)
    {
        $shop = Shop::findOne(['id' => $id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$shop) {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
        $user_exit = User::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'shop_id' => $id])->exists();
        if ($user_exit) {
            return [
                'code' => 1,
                'msg' => '请先删除门店下的核销员',
            ];
        }
        $shop->is_delete = 1;
        if ($shop->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    public function actionShopEdit($id = null)
    {
        $shop = Shop::findOne(['id' => $id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$shop) {
            $shop = new Shop();
        }
        if (\Yii::$app->request->isPost) {
            $form = new ShopForm();
            $form->store_id = $this->store->id;
            $form->shop = $shop;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        foreach ($shop as $index => $value) {
            if (in_array($index, ['content'])) {
                continue;
            }
            $shop[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('shop-edit', [
            'shop' => $shop,
        ]);
    }

    //门店设为默认
    public function actionShopUpDown($id = null, $type = 1)
    {
        $shop = Shop::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$shop) {
            return [
                'code' => 1,
                'msg' => '门店不存在或已删除，请刷新重试',
            ];
        }
        if ($type == 1) {
            if ($shop->is_default == 1) {
                return [
                    'code' => 1,
                    'msg' => '门店已经是默认门店，请刷新重试',
                ];
            }
            $shop->is_default = 1;
            $shop_1 = Shop::findOne(['is_default' => 1, 'store_id' => $this->store->id]);
            if ($shop_1) {
                $shop_1->is_default = 0;
                $shop_1->save();
            }
        } else {
            if ($shop->is_default == 0) {
                return [
                    'code' => 1,
                    'msg' => '门店已经关闭默认门店，请刷新重试',
                ];
            }
            $shop->is_default = 0;
        }
        if ($shop->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($shop->errors as $error) {
                return [
                    'code' => 1,
                    'msg' => $error[0],
                ];
            }
        }
    }

    //导航设置
    public function actionNavbar()
    {
        $navbar = AppNavbar::getNavbar($this->store->id);
        foreach ($navbar['navs'] as $index => $value) {
            if ($value['open_type'] == 'web') {
                $navbar['navs'][$index]['web'] = urldecode($value['web']);
            }
        }
        if (\Yii::$app->request->isPost) {
            $form = new NavbarEditForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            return $form->save();
        }
        if (\Yii::$app->request->isGet && \Yii::$app->request->isAjax) {
            //TODO option::get
            $navigation_bar_color = Option::get('navigation_bar_color', $this->store->id, 'app', [
                'frontColor' => '#000000',
                'backgroundColor' => '#ffffff',
                'bottomBackgroundColor' => '#ffffff',
            ]);

            $value = (array)$navigation_bar_color;
            $default = [
                'frontColor' => '#000000',
                'backgroundColor' => '#ffffff',
                'bottomBackgroundColor' => '#ffffff',
            ];

            if ($navigation_bar_color && $diff = array_diff_key($default, $value)) {
                $value = array_merge($value, $diff);
                $navigation_bar_color = (object)$value;
            }

            return [
                'code' => 0,
                'data' => [
                    'navbar' => $navbar,
                    'navigation_bar_color' => $navigation_bar_color,
                ],
            ];
        }
        if (\Yii::$app->request->isGet && !\Yii::$app->request->isAjax) {
            return $this->render('navbar');
        }
    }

    //导航设置-恢复默认
    public function actionNavbarReset()
    {
        Option::remove('navigation_bar_color', $this->store->id, 'app');
        Option::remove('navbar', $this->store->id, 'app');

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '更新成功'
        ];
    }

    //邮件设置
    public function actionMail()
    {
        $list = MailSetting::findOne(['store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$list) {
            $list = new MailSetting();
        }
        if (\Yii::$app->request->isPost) {
            $form = new MailForm();
            $post = \Yii::$app->request->post();
            if ($post['status'] == 1) {
                $form->scenario = 'SUCCESS';
            }
            $form->store_id = $this->store->id;
            $form->list = $list;
            $form->attributes = $post;
            return $form->save();
        } else {
            foreach ($list as $index => $value) {
                $list[$index] = str_replace("\"", "&quot;", $value);
            }
            return $this->render('mail', [
                'list' => $list,
            ]);
        }
    }

    //用户中心
    public function actionUserCenter()
    {
        $form = new UserCenterForm();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->saveData();
        } else {
            if (\Yii::$app->request->isAjax) {
                $data = $form->getData();
                return $data;
            } else {
                return $this->render('user-center');
            }
        }


        $model = new UserCenterMenu();
        $model->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            Option::set('user_center_bg', \Yii::$app->request->post('user_center_bg'), $this->store->id, 'app');
            return $model->saveMenuList(\Yii::$app->request->post('model'));
        } else {
            return $this->render('user-center', [
                'menu_list' => $model->getMenuList(),
                'user_center_bg' => Option::get('user_center_bg', $this->store->id, 'app', \Yii::$app->request->baseUrl . '/statics/images/img-user-bg.png'),
            ]);
        }
    }

    public function actionForm()
    {
        $form_list = Form::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $form = new SubmitFormForm();
            $form->store_id = $this->store->id;
            $form->attributes = $post;
            return $form->save();
        }
        return $this->render('form', [
            'form_list' => \Yii::$app->serializer->encode($form_list),
            'is_form' => Option::get('is_form', $this->store->id, 'admin', 0),
            'form_name' => Option::get('form_name', $this->store->id, 'admin', '表单名称'),
        ]);
    }

    //小程序页面大全
    public function actionWxappPages()
    {
        return $this->render('wxapp-pages');
    }

    //订单消息
    public function actionOrderMessage()
    {
        $form = new OrderMessageForm();
        $form->store_id = $this->store->id;
        $form->limit = 20;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('order-message', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
        ]);
    }

//    区域限制购买
    public function actionTerritorialIndex()
    {
        $url = \Yii::$app->urlManager->createUrl(['mch/store/territorial-limitation']);
        \Yii::$app->response->redirect($url)->send();
        return;
        $model = TerritorialLimitation::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ])->all();
        return $this->render('territorial-index', [
            'model' => $model,
        ]);
    }

    public function actionTerritorialDel($id = null)
    {
        return;
        $model = TerritorialLimitation::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
            return;
        }
        $model->is_delete = 1;
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        }
    }

    public function actionTerritorialLimitation($id = null)
    {
        $model = TerritorialLimitation::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        $error_data = TerritorialLimitation::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->andWhere(['!=', 'id', $model->id])->all();
        if (count($error_data) >= 1) {
            foreach ($error_data as $value) {
                if ($value['id'] == $model->id) {
                    continue;
                }
                $value->is_delete = 1;
                $value->save();
            }
        }
        if (!$model) {
            $model = new TerritorialLimitation();
        }
        if ($model->detail == 'null') {
            $model->detail = [];
        }
        if (\Yii::$app->request->isPost) {
            $form = new TerritorialLimitationForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $form->territorial_limitation = $model;
            return $form->save();
        } else {
//            $province = DistrictArr::getRules();
            $province = DistrictArr::getTerritorial();
            return $this->render('territorial-limitation', [
                'model' => $model,
                'province_list' => $province,
            ]);
        }
    }

    // 各地区满额配送
    public function actionOfferPriceEdit()
    {
        $form = new OfferPriceForm();
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post('data');
            $form->attributes = $data;
            $form->store_id = $this->store->id;
            return $form->save();
        } else {
            $form->store_id = $this->store->id;
            $arr = $form->search();
            return $this->render('offer-price-edit', [
                'province_list' => \Yii::$app->serializer->encode($arr['newList']),
                'ruleList' => \Yii::$app->serializer->encode($arr['ruleList']),
                'is_enable' => $arr['is_enable'],
                'total_price' => $arr['total_price'],
            ]);
        }
    }

    /**
     * 小程序商城 禁用|解禁
     * @return array|mixed
     */
    public function actionDisabled()
    {
        $data = \Yii::$app->request->get();
        $common = new CommonAppDisabled();
        $common->attributes = $data;
        $disabled = $common->disabled();

        return $disabled;
    }

    /**
     * 多选操作 禁用|解禁
     */
    public function actionMultiSelectDisabled()
    {
        $data = \Yii::$app->request->get();
        $common = new CommonAppDisabled();
        $common->status = $data['status'];
        $common->storeIds = $data['storeIds'];
        $disabled = $common->multiSelectDisabled();

        return $disabled;
    }

    /**
     * 所有商城禁用 | 解禁
     */
    public function actionAllDisabled()
    {
        $data = \Yii::$app->request->get();
        $common = new CommonAppDisabled();
        $common->status = $data['status'];
        $disabled = $common->allDisabled();

        return $disabled;
    }

    /**
     * 获取小程序菜单 可跳转链接菜单
     * @return mixed
     */
    public function actionPickLink()
    {
        $userAuth = $this->getUserAuth();
        $model = new PickLinkForm();
        $model->userAuth = $userAuth;
        $pickLink = $model->getPickLink();

        return $pickLink;
    }

    /**
     * 获取小程序底部菜单 可跳转的链接
     * @return mixed|string
     */
    public function actionNavPickLink()
    {
        $userAuth = $this->getUserAuth();
        $model = new PickLinkForm();
        $model->userAuth = $userAuth;
        $navPickLink = $model->getNavPickLink();

        return $navPickLink;
    }

    public function actionWxTitle()
    {
        $model = new WxForm();
        if (\Yii::$app->request->isAjax) {
            $model->data = \Yii::$app->request->post('model');
            $store = $model->store();

            return $store;
        }

        $list = $model->index();

        return $this->render('wx-title', ['list' => $list]);
    }

    public function actionService()
    {
        $option = Option::get('good_services', $this->store->id, 'admin', '');
        $option = $option ? json_encode($option) : [];
        $list = !empty($option) ? json_decode($option, true) : [];

        return $this->render('service', ['list' => $list]);
    }

    public function actionServiceEdit()
    {
        $option = Option::get('good_services', $this->store->id, 'admin', '');
        $option = $option ? json_encode($option) : [];
        $optionArr = !empty($option) ? json_decode($option, true) : [];

        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();

            // 数据记录为空时
            if (empty($option)) {
                $services[] = [
                    'id' => 1,
                    'service' => $data['service'],
                    'is_default' => 1,
                ];
                $option = Option::set('good_services', $services, $this->store->id, 'admin');
            } else {
                // ID为空是新增
                if (empty($data['id'])) {
                    $ids = [];
                    foreach ($optionArr as $item) {
                        $ids[] = $item['id'];
                    }
                    $id = max($ids) + 1;

                    $optionArr[] = [
                        'id' => $id,
                        'service' => $data['service'],
                        'is_default' => 0,
                    ];

                    $option = Option::set('good_services', $optionArr, $this->store->id, 'admin');
                } else {
                    // 编辑
                    foreach ($optionArr as &$item) {
                        if ($item['id'] == $data['id']) {
                            $item['service'] = $data['service'];
                            break;
                        }
                    }

                    $option = Option::set('good_services', $optionArr, $this->store->id, 'admin');
                }
            }

            if ($option) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            }

            return [
                'code' => 1,
                'msg' => '保存失败',
            ];
        }

        $detail = [];
        $id = \Yii::$app->request->get('id');
        foreach ($optionArr as $item) {
            if ($item['id'] == $id) {
                $detail = $item;
                break;
            }
        }

        return $this->render('service-edit', ['service' => $detail]);
    }

    public function actionSetDefault()
    {
        $option = Option::get('good_services', $this->store->id, 'admin', '');
        $option = json_encode($option);
        $optionArr = json_decode($option, true);

        $data = \Yii::$app->request->get();
        foreach ($optionArr as &$item) {
            $item['is_default'] = 0;
            if ($item['id'] == $data['id'] && $data['status'] == 1) {
                $item['is_default'] = 1;
            }
        }

        $option = Option::set('good_services', $optionArr, $this->store->id, 'admin');

        return [
            'code' => 0,
            'msg' => '状态更新成功',
        ];
    }

    public function actionServiceDel()
    {
        $option = Option::get('good_services', $this->store->id, 'admin', '');
        $option = json_encode($option);
        $optionArr = json_decode($option, true);

        $id = \Yii::$app->request->get('id');
        foreach ($optionArr as $k => $item) {
            if ($item['id'] == $id) {
                unset($optionArr[$k]);
                break;
            }
        }

        if (empty($optionArr)) {
            $option = Option::find()->where(['store_id' => $this->store->id, 'name' => 'good_services', 'group' => 'admin'])->one();
            $option->delete();
        } else {
            $newOptionArr = array_values($optionArr);
            $option = Option::set('good_services', $newOptionArr, $this->store->id, 'admin');
        }

        return [
            'code' => 0,
            'msg' => '删除成功！'
        ];
    }
}
