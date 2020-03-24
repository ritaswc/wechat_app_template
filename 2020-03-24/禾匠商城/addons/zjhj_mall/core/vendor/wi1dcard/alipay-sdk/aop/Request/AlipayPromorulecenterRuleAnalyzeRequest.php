<?php
/**
 * ALIPAY API: alipay.promorulecenter.rule.analyze request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-09 17:38:20
 */

namespace Alipay\Request;

class AlipayPromorulecenterRuleAnalyzeRequest extends AbstractAlipayRequest
{
    /**
     * 业务id
     **/
    private $bizId;
    /**
     * 规则id
     **/
    private $ruleUuid;
    /**
     * 支付宝用户id
     **/
    private $userId;

    public function setBizId($bizId)
    {
        $this->bizId = $bizId;
        $this->apiParams['biz_id'] = $bizId;
    }

    public function getBizId()
    {
        return $this->bizId;
    }

    public function setRuleUuid($ruleUuid)
    {
        $this->ruleUuid = $ruleUuid;
        $this->apiParams['rule_uuid'] = $ruleUuid;
    }

    public function getRuleUuid()
    {
        return $this->ruleUuid;
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
