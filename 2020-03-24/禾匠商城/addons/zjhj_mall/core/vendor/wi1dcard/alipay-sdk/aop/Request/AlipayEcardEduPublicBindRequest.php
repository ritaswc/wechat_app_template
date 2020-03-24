<?php
/**
 * ALIPAY API: alipay.ecard.edu.public.bind request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:16:41
 */

namespace Alipay\Request;

class AlipayEcardEduPublicBindRequest extends AbstractAlipayRequest
{
    /**
     * 机构编码
     **/
    private $agentCode;
    /**
     * 公众账号协议Id
     **/
    private $agreementId;
    /**
     * 支付宝userId
     **/
    private $alipayUserId;
    /**
     * 一卡通姓名
     **/
    private $cardName;
    /**
     * 一卡通卡号
     **/
    private $cardNo;
    /**
     * 公众账号id
     **/
    private $publicId;

    public function setAgentCode($agentCode)
    {
        $this->agentCode = $agentCode;
        $this->apiParams['agent_code'] = $agentCode;
    }

    public function getAgentCode()
    {
        return $this->agentCode;
    }

    public function setAgreementId($agreementId)
    {
        $this->agreementId = $agreementId;
        $this->apiParams['agreement_id'] = $agreementId;
    }

    public function getAgreementId()
    {
        return $this->agreementId;
    }

    public function setAlipayUserId($alipayUserId)
    {
        $this->alipayUserId = $alipayUserId;
        $this->apiParams['alipay_user_id'] = $alipayUserId;
    }

    public function getAlipayUserId()
    {
        return $this->alipayUserId;
    }

    public function setCardName($cardName)
    {
        $this->cardName = $cardName;
        $this->apiParams['card_name'] = $cardName;
    }

    public function getCardName()
    {
        return $this->cardName;
    }

    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        $this->apiParams['card_no'] = $cardNo;
    }

    public function getCardNo()
    {
        return $this->cardNo;
    }

    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
        $this->apiParams['public_id'] = $publicId;
    }

    public function getPublicId()
    {
        return $this->publicId;
    }
}
