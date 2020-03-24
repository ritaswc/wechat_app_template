<?php
/**
 * ALIPAY API: alipay.ebpp.pdeduct.sign.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-04 11:19:20
 */

namespace Alipay\Request;

class AlipayEbppPdeductSignCancelRequest extends AbstractAlipayRequest
{
    /**
     * 此值只是供代扣中心做最后的渠道统计用，并不做值是什么的强校验，只要不为空就可以
     **/
    private $agentChannel;
    /**
     * 标识发起方的ID，从服务窗发起则为appId的值，appId即开放平台分配给接入ISV的id，此处也可以随便真其它值，只要能标识唯一即可
     **/
    private $agentCode;
    /**
     * 支付宝代扣协议ID
     **/
    private $agreementId;
    /**
     * 需要用户首先处于登陆态，然后访问https://ebppprod.alipay.com/deduct/enterMobileicPayPassword.htm?cb=自己指定的回跳连接地址,访问页面后会进到独立密码校验页，用户输入密码校验成功后，会生成token回调到指定的回跳地址，如果设置cb=www.baidu.com则最后回调的内容是www.baidu.com?token=312314ADFDSFAS,然后签约时直接取得地址后token的值即可
     **/
    private $payPasswordToken;
    /**
     * 用户ID
     **/
    private $userId;

    public function setAgentChannel($agentChannel)
    {
        $this->agentChannel = $agentChannel;
        $this->apiParams['agent_channel'] = $agentChannel;
    }

    public function getAgentChannel()
    {
        return $this->agentChannel;
    }

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

    public function setPayPasswordToken($payPasswordToken)
    {
        $this->payPasswordToken = $payPasswordToken;
        $this->apiParams['pay_password_token'] = $payPasswordToken;
    }

    public function getPayPasswordToken()
    {
        return $this->payPasswordToken;
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
