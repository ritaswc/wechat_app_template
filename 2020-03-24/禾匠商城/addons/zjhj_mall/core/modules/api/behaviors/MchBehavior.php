<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/6
 * Time: 10:41
 */

namespace app\modules\api\behaviors;

use app\hejiang\ApiResponse;
use app\models\Mch;
use app\models\Model;
use yii\base\ActionFilter;
use yii\web\Controller;

class MchBehavior extends ActionFilter
{
    public $actions;

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * @param \yii\base\InlineAction $e
     */
    public function beforeAction($e)
    {
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->response->data = new ApiResponse(-1, '请先登录。');
            return false;
        }
        $mch = Mch::findOne([
            'user_id' => \Yii::$app->user->id,
            'is_delete' => Model::IS_DELETE_FALSE,
            'review_status' => 1,
        ]);
        if (!$mch) {
            \Yii::$app->response->data = new ApiResponse(1, '请先申请商户入驻。');
            return false;
        }
        $e->controller->mch = $mch;
        return true;
    }
}
