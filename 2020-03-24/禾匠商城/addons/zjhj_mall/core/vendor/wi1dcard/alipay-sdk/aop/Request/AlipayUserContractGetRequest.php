<?php
/**
 * ALIPAY API: alipay.user.contract.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 20:23:18
 */

namespace Alipay\Request;

class AlipayUserContractGetRequest extends AbstractAlipayRequest
{
    /**
     * 订购者支付宝ID。session与subscriber_user_id二选一即可。
     **/
    private $subscriberUserId;

    public function setSubscriberUserId($subscriberUserId)
    {
        $this->subscriberUserId = $subscriberUserId;
        $this->apiParams['subscriber_user_id'] = $subscriberUserId;
    }

    public function getSubscriberUserId()
    {
        return $this->subscriberUserId;
    }
}
