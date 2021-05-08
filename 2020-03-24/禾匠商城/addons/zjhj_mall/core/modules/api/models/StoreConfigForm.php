<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/8
 * Time: 13:53
 */

namespace app\modules\api\models;

use app\models\alipay\MpConfig;
use app\models\Option;
use app\models\Setting;
use app\models\Store;
use app\models\StorePermission;
use app\modules\mch\models\ShareCustomForm;

class StoreConfigForm extends ApiModel
{
    public static function getConfig()
    {
        // 服务器图片
        $form = new StoreFrom();
        $wxappImg = $form->search();

        // 小程序页面标题
        $wxForm = new WxForm();
        $wxBarTitle = $wxForm->index();

        // 商城的配置信息
        $storeConfig = new self();
        $store = $storeConfig->store;
        $data = self::getData($store);

        // 支付宝配置
        $alipay_mp_config = MpConfig::get($store->id);

        $config = [
            'share_setting' => $storeConfig->getShareSetting(),
            'wxapp_img' => $wxappImg,
            'wx_bar_title' => $wxBarTitle,
            'store_name' => $store->name,
            'contact_tel' => $store->contact_tel,
            'show_customer_service' => $store->show_customer_service,
            'store' => $data,
            'alipay_mp_config' => [
                'cs_tnt_inst_id' => $alipay_mp_config->cs_tnt_inst_id,
                'cs_scene' => $alipay_mp_config->cs_scene,
            ],
            'permission_list' => StorePermission::getOpenPermissionList($store),
        ];
        return $config;
    }

    public static function getData(Store $store)
    {
        // option中存储的配置信息
        $option = Option::getList('step,service,web_service,web_service_url,wxapp,quick_navigation,phone_auth,good_negotiable,quick_map,web_service_status', $store->id, 'admin', '');
        foreach ($option as $index => $value) {
            if (in_array($index, ['wxapp'])) {
                $option[$index] = json_decode($value, true);
            }
        }
        if ($option['good_negotiable']) {
            $option['good_negotiable'] = \Yii::$app->serializer->decode($option['good_negotiable']);
        } else {
            $option['good_negotiable'] = (object)array();
        }

        if ($option['quick_navigation']) {
            $option['quick_navigation'] = \Yii::$app->serializer->decode($option['quick_navigation']);
        } else {
            $option['quick_navigation'] = (object)array();
        }
        if ($option['step']) {
            $option['step']['currency_name'] = $option['step']['currency_name'] ? $option['step']['currency_name'] : '活力币';
        };

        $shareCustom = new ShareCustomForm();
        $shareCustom->store_id = $store->id;
        $shareCustomData = $shareCustom->getData()['data'];

        $data = (object)[
            'id' => $store->id,
            'name' => $store->name,
            'copyright' => $store->copyright,
            'copyright_pic_url' => $store->copyright_pic_url,
            'copyright_url' => $store->copyright_url,
            'contact_tel' => $store->contact_tel,
            'show_customer_service' => $store->show_customer_service,
            'cat_style' => $store->cat_style,
            'address' => $store->address,
            'is_offline' => $store->is_offline,
            'is_coupon' => 1,
            'is_comment' => $store->is_comment,
            'is_share_price' => $store->is_share_price,
            'is_member_price' => $store->is_member_price,
            'dial' => $store->dial,
            'dial_pic' => $store->dial_pic,
            'service' => $option['service'],
            'cut_thread' => $store->cut_thread,
            'option' => $option,
            'purchase_frame' => $store->purchase_frame,
            'is_sales' => $store->is_sales,
            'quick_navigation' => $option['quick_navigation'],
            'good_negotiable' => $option['good_negotiable'],
            'buy_member' => $store->buy_member,
            'logo'  => $store->logo,
            'is_official_account' => $store->is_official_account,
            'share_custom_data' => $shareCustomData
        ];
        return $data;
    }

    private function getShareSetting()
    {
        //分销设置
        $shareSetting = Setting::find()->alias('s')
            ->where(['s.store_id' => $this->store->id])
            ->leftJoin('{{%qrcode}} q', 'q.store_id=s.store_id and q.is_delete=0')
            ->select(['s.*', 'q.qrcode_bg'])
            ->asArray()->one();
        if (is_null($shareSetting)) {
            $shareSetting = new Setting();
            $shareSetting->store_id = $this->store->id;
            $shareSetting->save();
            $shareSetting = Setting::find()->alias('s')
                ->where(['s.store_id' => $this->store->id])
                ->leftJoin('{{%qrcode}} q', 'q.store_id=s.store_id and q.is_delete=0')
                ->select(['s.*', 'q.qrcode_bg'])
                ->asArray()->one();
        }
        $shareSetting['qrcode_bg'] = "";
        return $shareSetting;
    }
}
