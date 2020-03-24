<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/18
 * Time: 14:17
 */

namespace app\modules\mch\controllers;

use app\models\Article;
use app\modules\mch\models\MchModel;
use app\modules\mch\models\Model;
use app\modules\mch\models\ArticleForm;

class ArticleController extends Controller
{
    public function actionIndex($cat_id = 1)
    {
        $list = Article::find()->where([
            'store_id' => $this->store->id,
            'article_cat_id' => $cat_id,
            'is_delete' => 0,
        ])->orderBy('sort ASC,addtime DESC')->all();
        if (empty($list) && $cat_id == 1) {
            $item = new Article();
            $item->article_cat_id = 1;
            $item->title = '关于我们';
            $list[] = $item;
        }
        return $this->render('index', [
            'list' => $list,
            'cat_id' => $cat_id,
        ]);
    }

    public function actionEdit($cat_id, $id = null)
    {
        $model = Article::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
            'article_cat_id' => $cat_id,
        ]);
        if (!$model) {
            $model = new Article();
        }
        if (\Yii::$app->request->isPost) {
            $form = new ArticleForm();
            $post = \Yii::$app->request->post();
            $form->attributes = $post;
            $form->model = $model;
            $form->store_id = $this->store->id;
            $form->article_cat_id = $cat_id;

            return $form->save();
        } else {
            foreach ($model as $index => $value) {
                $model[$index] = str_replace("\"", "&quot;", $value);
            }
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Article::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        if ($model) {
            $model->is_delete = 1;
            if (!$model->save()) {
                return (new MchModel())->getErrorResponse($model);
            }
        }
        return [
            'code' => 0,
            'msg' => '删除成功',
        ];
    }
}
