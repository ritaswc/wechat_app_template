<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/24 9:22
 */


namespace app\controllers;

/**
 * 定时任务接收控制器
 * Class TaskController
 * @package app\controllers
 */
class TaskController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function actionIndex($token)
    {
        try {
            \Yii::$app->task->run($token);
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => null,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'data' => $e->getTraceAsString(),
            ];
        }
    }
}
