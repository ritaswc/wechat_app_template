<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\diy;


use app\models\Option;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\diy\CatForm;
use app\modules\mch\models\diy\DiyPageEditForm;
use app\modules\mch\models\diy\DiyPageForm;
use app\modules\mch\models\diy\DiyTemplateEditForm;
use app\modules\mch\models\diy\DiyTemplateForm;
use app\modules\mch\models\diy\GoodsForm;
use app\modules\mch\models\diy\NavBForm;
use app\modules\mch\models\diy\RubikForm;


class DiyController extends Controller
{
    /**
     * 模板列表
     * @return array
     */
    public function actionIndex()
    {
        $model = new DiyTemplateForm();
        $res = $model->getList();

        return $this->render('index', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    /**
     * 添加编辑模板
     * @return \app\hejiang\ValidationErrorResponse|array|string
     */
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $model = new DiyTemplateEditForm();
                $model->attributes = \Yii::$app->request->post();
                $res = $model->save();

                return $res;
            }

            if (\Yii::$app->request->isGet) {
                $model = new DiyTemplateForm();
                $model->id = \Yii::$app->request->get('template_id');
                $res = $model->detail();

                return $res;
            }
        }

        $data = Option::get('overrun', 0, 'admin', [
            'max_picture' => 1,
            'max_diy' => 20,
            'over_picture' => 0,
            'over_diy' => 0,
        ]);
        $max_diy = $data['max_diy'];
        if ($data['over_diy'] == 1) {
            $max_diy = -1;
        }
        return $this->render('edit', [
            'max_diy' => $max_diy
        ]);
    }

    /**
     * 删除模板
     * @return array
     */
    public function actionDelete()
    {
        $model = new DiyTemplateForm();
        $model->id = \Yii::$app->request->get('id');
        $res = $model->delete();

        return $res;
    }

    /**
     * 页面列表
     * @return string
     */
    public function actionPage()
    {
        $model = new DiyPageForm();
        $res = $model->getList();

        return $this->render('page', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }

    /**
     * 页面编辑
     */
    public function actionPageEdit()
    {
        $id = \Yii::$app->request->get('id');
        if (\Yii::$app->request->isAjax) {
            $model = new DiyPageEditForm();
            $model->attributes = \Yii::$app->request->post();
            $model->id = $id;
            $res = $model->save();

            return $res;
        }

        $model = new DiyPageForm();
        $model->attributes = \Yii::$app->request->get();
        $res = $model->detail();


        return $this->render('page-edit', [
            'templateList' => $res['templateList'],
            'detail' => $res['detail'],
        ]);
    }

    /**
     * 页面删除
     * @return array
     */
    public function actionPageDelete()
    {
        $model = new DiyPageForm();
        $model->id = \Yii::$app->request->get('id');
        $res = $model->delete();

        return $res;
    }

    public function actionGetCat()
    {
        $form = new CatForm();
        $form->type = \Yii::$app->request->get('type');
        $form->page = \Yii::$app->request->get('page', 1);
        $form->keyword = \Yii::$app->request->get('keyword');
        $form->limit = 8;
        return $form->search();
    }

    public function actionGetGoods()
    {
        $form = new GoodsForm();
        $form->type = \Yii::$app->request->get('type');
        $form->page = \Yii::$app->request->get('page', 1);
        $form->cat = \Yii::$app->request->get('cat', 0);
        $form->mch = \Yii::$app->request->get('mch', 0);
        $form->limit = 8;
        return $form->search();
    }

    public function actionGetRubik()
    {
        $form = new RubikForm();
        $form->id = \Yii::$app->request->get('id');
        return $form->search();
    }

    /**
     * 页面状态修改
     * @return array
     */
    public function actionPageUpdateStatus()
    {
        $model = new DiyPageForm();
        $model->id = \Yii::$app->request->get('id');
        $model->status = \Yii::$app->request->get('status');
        $res = $model->updateStatus();

        return $res;
    }

    /**
     * 页面是否设置成首页
     * @return array
     */
    public function actionPageUpdateIndex()
    {
        $model = new DiyPageForm();
        $model->id = \Yii::$app->request->get('id');
        $model->status = \Yii::$app->request->get('status');
        $res = $model->updateIndex();

        return $res;
    }

    // 获取导航图标、轮播图
    public function actionGetNavBanner()
    {
        $form = new NavBForm();
        $form->type = \Yii::$app->request->get('type');
        $form->page = \Yii::$app->request->get('page', 1);
        $form->limit = 8;
        return $form->search();
    }
}