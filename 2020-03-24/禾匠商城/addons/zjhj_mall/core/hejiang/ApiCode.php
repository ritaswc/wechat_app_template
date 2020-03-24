<?php

namespace app\hejiang;

/**
 * Structured HTTP API Response Class
 *
 * @property int $code
 * @property string $msg
 * @property array|object $data
 */
class ApiCode
{
    /**
     *  状态码：成功
     */
    const CODE_SUCCESS = 0;

    /**
     * 状态码：失败
     */
    const CODE_ERROR = 1;
}