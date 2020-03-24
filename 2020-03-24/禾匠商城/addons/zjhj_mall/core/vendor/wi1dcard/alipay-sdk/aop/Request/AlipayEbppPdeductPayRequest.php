<?php
/**
 * ALIPAY API: alipay.ebpp.pdeduct.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-18 17:15:00
 */

namespace Alipay\Request;

class AlipayEbppPdeductPayRequest extends AbstractAlipayRequest
{
    /**
     * 渠道码，如用户通过机构通过服务窗进来签约则是PUBLICFORM，此值可随意传，只要不空就行
     **/
    private $agentChannel;
    /**
     * 二级渠道码，预留字段
     **/
    private $agentCode;
    /**
     * 支付宝代扣协议Id
     **/
    private $agreementId;
    /**
     * 账期
     **/
    private $billDate;
    /**
     * 户号，缴费单位用于标识每一户的唯一性的
     **/
    private $billKey;
    /**
     * 扩展参数。必须以key value形式定义，
     * 转为json为格式：{"key1":"value1","key2":"value2",
     * "key3":"value3","key4":"value4"}
     * 后端会直接转换为MAP对象，转换异常会报参数格式错误
     **/
    private $extendField;
    /**
     * 滞纳金
     **/
    private $fineAmount;
    /**
     * 备注信息
     **/
    private $memo;
    /**
     * 商户外部业务流水号
     **/
    private $outOrderNo;
    /**
     * 扣款金额，支付总金额，包含滞纳金
     **/
    private $payAmount;
    /**
     * 商户PartnerId
     **/
    private $pid;
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

    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;
        $this->apiParams['bill_date'] = $billDate;
    }

    public function getBillDate()
    {
        return $this->billDate;
    }

    public function setBillKey($billKey)
    {
        $this->billKey = $billKey;
        $this->apiParams['bill_key'] = $billKey;
    }

    public function getBillKey()
    {
        return $this->billKey;
    }

    public function setExtendField($extendField)
    {
        $this->extendField = $extendField;
        $this->apiParams['extend_field'] = $extendField;
    }

    public function getExtendField()
    {
        return $this->extendField;
    }

    public function setFineAmount($fineAmount)
    {
        $this->fineAmount = $fineAmount;
        $this->apiParams['fine_amount'] = $fineAmount;
    }

    public function getFineAmount()
    {
        return $this->fineAmount;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;
        $this->apiParams['memo'] = $memo;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setOutOrderNo($outOrderNo)
    {
        $this->outOrderNo = $outOrderNo;
        $this->apiParams['out_order_no'] = $outOrderNo;
    }

    public function getOutOrderNo()
    {
        return $this->outOrderNo;
    }

    public function setPayAmount($payAmount)
    {
        $this->payAmount = $payAmount;
        $this->apiParams['pay_amount'] = $payAmount;
    }

    public function getPayAmount()
    {
        return $this->payAmount;
    }

    public function setPid($pid)
    {
        $this->pid = $pid;
        $this->apiParams['pid'] = $pid;
    }

    public function getPid()
    {
        return $this->pid;
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
