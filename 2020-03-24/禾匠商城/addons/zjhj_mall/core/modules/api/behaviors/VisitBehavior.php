<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/27
 * Time: 17:26
 */


namespace app\modules\api\behaviors;

use app\models\MchVisitLog;
use yii\base\Behavior;
use yii\helpers\VarDumper;
use yii\web\Controller;

class VisitBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($e)
    {
        if (\Yii::$app->requestedRoute == 'api/mch/index/shop') {
            $log = new MchVisitLog();
            $log->user_id = \Yii::$app->user->id;
            $log->mch_id = \Yii::$app->request->get('mch_id');
            $log->addtime = time();
            $log->visit_date = date('Y-m-d');
            $log->save();
        }
    }
}
