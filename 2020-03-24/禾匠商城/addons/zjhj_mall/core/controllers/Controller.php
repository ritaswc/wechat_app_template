<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/14
 * Time: 9:32
 */

namespace app\controllers;

use app\behaviors\BargainBehavior;
use app\behaviors\LotteryBehavior;
use app\behaviors\CouponBehavior;
use app\behaviors\OrderBehavior;
use app\behaviors\PintuanBehavior;
use app\behaviors\StepBehavior;
use yii\web\Response;
use app\hejiang\ValidationErrorResponse;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'order' => [
                'class' => OrderBehavior::className(),
            ],
            'coupon' => [
                'class' => CouponBehavior::className(),
            ],
            'pintuan' => [
                'class' => PintuanBehavior::className(),
            ],
            'bargain' => [
                'class' => BargainBehavior::className(),
            ],
            'lottery' => [
                'class' => LotteryBehavior::className(),
            ],
            'step' => [
                'class' => StepBehavior::className(),
            ],
        ];
    }

    public function init()
    {
        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, [$this, 'beforeSend']);
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeSend($event)
    {
        /* @var $response \yii\web\response */
        $response = $event->sender;
        /* @var $data \app\hejiang\ValidationErrorResponse */
        $data = &$response->data;

        if ($data instanceof ValidationErrorResponse) {
            $response->format = Response::FORMAT_JSON;
            $data = $data->raw;
        }
    }
}
