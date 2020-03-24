<?php

namespace Alipay\Signer;

class AlipayRSASigner extends AlipaySigner
{
    public function getSignType()
    {
        return 'RSA';
    }

    public function getSignAlgo()
    {
        return OPENSSL_ALGO_SHA1;
    }
}
