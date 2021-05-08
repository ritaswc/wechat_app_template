<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/18
 * Time: 14:17
 */

namespace app\modules\mch\controllers\group;

use app\models\Article;
use app\modules\mch\models\Model;

class ArticleController extends Controller
{
    public function actionEdit()
    {
        $model = Article::findOne([
            'store_id' => $this->store->id,
            'article_cat_id' => 3,
        ]);
        if (!$model) {
            $model = new Article();
            $model->article_cat_id = 3;
            $model->store_id = $this->store->id;
        }
        if (\Yii::$app->request->isPost) {
            $model->attributes = \Yii::$app->request->post();
            $model->article_cat_id = 3;
            $model->store_id = $this->store->id;
            if ($model->isNewRecord) {
                $model->addtime = time();
            }
            if ($model->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return (new Model())->getErrorResponse($model);
            }
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }
}
