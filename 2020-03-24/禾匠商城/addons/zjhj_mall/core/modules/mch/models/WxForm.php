<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models;

use app\hejiang\ApiCode;
use app\models\common\CommonWx;
use app\models\WxTitle;

class WxForm
{
    public $data;

    public function index()
    {
        $list = WxTitle::findAll([
            'store_id' => \Yii::$app->controller->store->id,
        ]);

        $common = new CommonWx();
        $defaultTitle = $common->wxDefaultTitle();

        foreach ($defaultTitle as $k => $item) {
            foreach ($list as $i) {
                if ($item['url'] === $i->url && $i->title) {
                    $defaultTitle[$k]['new_title'] = $i->title;
                    break;
                }
            }
        }

        return $defaultTitle;
    }

    public function store()
    {
        try {
            $transaction = \Yii::$app->db->beginTransaction();
            WxTitle::deleteAll(['store_id' => \Yii::$app->controller->store->id]);

            $storeId = \Yii::$app->controller->store->id;
            $count = 0;
            foreach ($this->data as $item) {
                $count++;
                $wxTitle = new WxTitle();
                $wxTitle->title = $item['title'];
                $wxTitle->url = $item['url'];
                $wxTitle->addtime = time();
                $wxTitle->store_id = $storeId;
                $wxTitle->save();
            }

            if ($count === count($this->data)) {
                $transaction->commit();

                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '保存成功'
                ];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

    }
}