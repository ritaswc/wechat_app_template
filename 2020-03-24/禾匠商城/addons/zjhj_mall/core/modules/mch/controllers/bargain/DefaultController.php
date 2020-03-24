<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/10
 * Time: 13:49
 */

namespace app\modules\mch\controllers\bargain;


use app\models\Banner;
use app\modules\mch\models\BannerForm;
use app\modules\mch\models\bargain\BargainInfoForm;
use app\modules\mch\models\bargain\SettingForm;

class DefaultController extends Controller
{
    // 砍价设置
    public function actionSetting()
    {
        $form = new SettingForm();
        $form->store_id = $this->store->id;
        $model = $form->search();
        return $this->render('setting', [
            'setting' => $model
        ]);
    }

    // 砍价设置保存
    public function actionSettingSave()
    {
        $model = \Yii::$app->request->post('model');
        $form = new SettingForm();
        $form->store_id = $this->store->id;
        $form->attributes = $model;
        return $form->save();
    }

    // 幻灯片列表
    public function actionSlide()
    {
        $form = new BannerForm();
        $form->type = 4;
        $res = $form->getList($this->store->id);
        return $this->render('slide', [
            'list' => $res[0],
            'pagination' => $res[1]
        ]);
    }

    // 幻灯片编辑
    public function actionSlideEdit($id = 0)
    {
        $banner = Banner::findOne(['id' => $id, 'type' => 4]);
        if (!$banner) {
            $banner = new Banner();
        }
        if (\Yii::$app->request->isPost) {
            $form = new BannerForm();
            $model = \Yii::$app->request->post('model');
            $form->attributes = $model;
            $form->store_id = $this->store->id;
            $form->banner = $banner;
            $form->type = 4;
            return $form->save();
        }
        foreach ($banner as $index => $value) {
            $banner[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('slide-edit', [
            'list' => $banner,
        ]);
    }

    // 幻灯片删除
    public function actionSlideDel($id = 0)
    {
        $banner = Banner::findOne(['id' => $id, 'is_delete' => 0, 'type' => 4]);
        if (!$banner) {
            return [
                'code' => 1,
                'msg' => '幻灯片不存在或已经删除',
            ];
        }
        $banner->is_delete = 1;
        if ($banner->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($banner->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    // 砍价信息
    public function actionBargain()
    {
        $form = new BargainInfoForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store = $this->store;
        $res = $form->search();
        return $this->render('bargain', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
            'row_count' => $res['pagination']->totalCount
        ]);
    }
}