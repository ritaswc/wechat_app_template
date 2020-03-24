<?php

namespace Alipay\Exception;

/**
 * 当 CURL 请求成功，但响应 HTTP Status 非 200 时抛出。
 */
class AlipayHttpException extends AlipayException
{
}
