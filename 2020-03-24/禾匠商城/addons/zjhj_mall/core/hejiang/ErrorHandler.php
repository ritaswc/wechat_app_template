<?php

namespace app\hejiang;

class ErrorHandler extends \yii\web\ErrorHandler
{
    public function convertExceptionToArray($exception)
    {
        $value = parent::convertExceptionToArray($exception);
        if ($exception->event_id) {
            $value['event_id'] = $exception->event_id;
        }
        $value = new \app\hejiang\ApiResponse(
            500,
            '请将「事件 ID」发送给我们，以便于我们进行问题追踪。',
            [
                'event_id' => $value['event_id'],
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'trace_string' => $exception->getTraceAsString(),
            ]
        );
        return $value;
    }
}
