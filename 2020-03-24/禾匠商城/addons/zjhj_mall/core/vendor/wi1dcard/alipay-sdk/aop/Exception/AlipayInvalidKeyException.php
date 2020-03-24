<?php

namespace Alipay\Exception;

/**
 * 当使用无效密钥（即无法被 OpenSSL 载入）时抛出。
 */
class AlipayInvalidKeyException extends AlipayOpenSslException
{
}
