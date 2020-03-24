<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 11:45
 */

namespace app\modules\mch\controllers;

use app\models\Option;

class HandleController extends Controller
{
    public function actionIndex()
    {
        $handle = Option::get('handle', 0, 'admin');
        $handle = json_decode($handle, true);
        if ($handle['status'] == 0 && !$this->is_admin) {
            $url = \Yii::$app->urlManager->createUrl(['mch/store/index']);
            return $this->redirect($url)->send();
        }
        return $this->render('index', [
            'handle' => $handle
        ]);
    }

    public function actionSetting()
    {
        if (!$this->is_admin) {
            $url = \Yii::$app->urlManager->createUrl(['mch/store/index']);
            return $this->redirect($url)->send();
        }
        if (\Yii::$app->request->isPost) {
            $list = [];
            $post = \Yii::$app->request->post();
            $list['status'] = $post['status'];
            $list['url'] = trim($post['url']);
            $list = \Yii::$app->serializer->encode($list);
            Option::set('handle', $list, 0, 'admin');
            $cacheKey = $this->getMenuCacheKey();
            \Yii::$app->cache->delete($cacheKey);
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            $model = Option::get('handle', 0, 'admin');
            $model = json_decode($model, true);
            return $this->render('setting', [
                'model' => $model
            ]);
        }
    }
}
