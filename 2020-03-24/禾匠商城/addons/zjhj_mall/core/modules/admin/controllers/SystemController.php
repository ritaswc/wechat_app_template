<?php

/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/22
 * Time: 16:08
 */

namespace app\modules\admin\controllers;

use app\models\common\admin\db\CommonDbOptimize;
use app\models\common\admin\store\CommonStoreUpload;
use app\models\common\admin\system\commonOverrunForm;
use app\models\UploadConfig;
use Yii;

class SystemController extends Controller
{
    /**
     * 数据库优化
     */
    public function actionDbOptimize()
    {
        if (Yii::$app->request->isPost) {
            $form = new CommonDbOptimize();
            $form->attributes = Yii::$app->request->post();
            return $form->run();
        } else {
            return $this->render('db-optimize');
        }
    }

    //上传设置
    public function actionUpload()
    {
        $model = UploadConfig::findOne([
            'store_id' => 0,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new UploadConfig();
        }
        if (\Yii::$app->request->isPost) {
            $form = new CommonStoreUpload();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store_id = $this->store->id;
            return $form->save();
        } else {
            $model->aliyun = json_decode($model->aliyun, true);
            $model->qcloud = json_decode($model->qcloud, true);
            $model->qiniu = json_decode($model->qiniu, true);
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    public function actionOverrun()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new commonOverrunForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            } else {
                $form = new commonOverrunForm();
                return $form->search();
            }
        }
        return $this->render('overrun');
    }
}
