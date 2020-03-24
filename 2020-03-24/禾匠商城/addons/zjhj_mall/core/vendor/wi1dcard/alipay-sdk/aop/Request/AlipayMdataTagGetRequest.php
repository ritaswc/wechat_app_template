<?php
/**
 * ALIPAY API: alipay.mdata.tag.get request
 *
 * @author auto create
 *
 * @since  1.0, 2015-03-11 14:09:56
 */

namespace Alipay\Request;

class AlipayMdataTagGetRequest extends AbstractAlipayRequest
{
    /**
     * 所需标签列表.
     **/
    private $requiredTags;
    /**
     * 用户的支付宝Id
     **/
    private $userId;

    public function setRequiredTags($requiredTags)
    {
        $this->requiredTags = $requiredTags;
        $this->apiParams['required_tags'] = $requiredTags;
    }

    public function getRequiredTags()
    {
        return $this->requiredTags;
    }

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
