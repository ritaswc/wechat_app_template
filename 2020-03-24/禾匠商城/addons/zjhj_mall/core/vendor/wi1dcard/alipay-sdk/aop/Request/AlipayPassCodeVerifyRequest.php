<?php
/**
 * ALIPAY API: alipay.pass.code.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:16:11
 */

namespace Alipay\Request;

class AlipayPassCodeVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 商户核销操作扩展信息
     **/
    private $extInfo;
    /**
     * 操作员id
     * 如果operator_type为1，则此id代表核销人员id
     * 如果operator_type为2，则此id代表核销机具id
     **/
    private $operatorId;
    /**
     * 操作员类型
     * 1 核销人员
     * 2 核销机具
     **/
    private $operatorType;
    /**
     * Alipass对应的核销码串
     **/
    private $verifyCode;

    public function setExtInfo($extInfo)
    {
        $this->extInfo = $extInfo;
        $this->apiParams['ext_info'] = $extInfo;
    }

    public function getExtInfo()
    {
        return $this->extInfo;
    }

    public function setOperatorId($operatorId)
    {
        $this->operatorId = $operatorId;
        $this->apiParams['operator_id'] = $operatorId;
    }

    public function getOperatorId()
    {
        return $this->operatorId;
    }

    public function setOperatorType($operatorType)
    {
        $this->operatorType = $operatorType;
        $this->apiParams['operator_type'] = $operatorType;
    }

    public function getOperatorType()
    {
        return $this->operatorType;
    }

    public function setVerifyCode($verifyCode)
    {
        $this->verifyCode = $verifyCode;
        $this->apiParams['verify_code'] = $verifyCode;
    }

    public function getVerifyCode()
    {
        return $this->verifyCode;
    }
}
