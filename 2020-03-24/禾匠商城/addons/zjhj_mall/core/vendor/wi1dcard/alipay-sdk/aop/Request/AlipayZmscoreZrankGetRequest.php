<?php
/**
 * ALIPAY API: alipay.zmscore.zrank.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 10:27:38
 */

namespace Alipay\Request;

class AlipayZmscoreZrankGetRequest extends AbstractAlipayRequest
{
    /**
     * 用户ID
     **/
    private $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->apiParams['user_id'] = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
