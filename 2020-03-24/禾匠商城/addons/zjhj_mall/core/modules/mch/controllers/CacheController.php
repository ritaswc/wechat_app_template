<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/9
 * Time: 11:54
 */

namespace app\modules\mch\controllers;

use app\models\common\admin\cache\CommonClearCache;
use Yii;

class CacheController extends Controller
{
    public function actionIndex()
    {
        // TODO 暂时注释
        // $this->checkIsAdmin();
        if (Yii::$app->request->isPost) {
            $common = new CommonClearCache();
            $common->attributes = Yii::$app->request->post();
            return $common->clear();
        } else {
            return $this->render('index');
        }
    }
}
