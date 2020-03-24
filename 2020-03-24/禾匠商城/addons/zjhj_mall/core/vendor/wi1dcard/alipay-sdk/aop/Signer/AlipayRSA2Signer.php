<?php

namespace Alipay\Signer;

class AlipayRSA2Signer extends AlipaySigner
{
    public function getSignType()
    {
        return 'RSA2';
    }

    public function getSignAlgo()
    {
        return OPENSSL_ALGO_SHA256;
    }
}
