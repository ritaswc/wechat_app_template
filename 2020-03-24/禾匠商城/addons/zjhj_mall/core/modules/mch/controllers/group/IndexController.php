<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/11/22
 * Time: 14:58
 */

namespace app\modules\mch\controllers\group;

class IndexController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['mch/group/goods/index']));
//        return $this->render('index');
    }
}
