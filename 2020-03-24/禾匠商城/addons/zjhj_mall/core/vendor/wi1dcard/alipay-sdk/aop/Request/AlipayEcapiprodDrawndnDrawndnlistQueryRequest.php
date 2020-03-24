<?php
/**
 * ALIPAY API: alipay.ecapiprod.drawndn.drawndnlist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-29 11:34:45
 */

namespace Alipay\Request;

class AlipayEcapiprodDrawndnDrawndnlistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 授信编号
     **/
    private $creditNo;
    /**
     * 身份证号码
     **/
    private $entityCode;
    /**
     * 客户的姓名
     **/
    private $entityName;
    /**
     * 融资平台分配给ISV的编码
     **/
    private $isvCode;
    /**
     * 融资平台分配给小贷公司的机构编码
     **/
    private $orgCode;

    public function setCreditNo($creditNo)
    {
        $this->creditNo = $creditNo;
        $this->apiParams['credit_no'] = $creditNo;
    }

    public function getCreditNo()
    {
        return $this->creditNo;
    }

    public function setEntityCode($entityCode)
    {
        $this->entityCode = $entityCode;
        $this->apiParams['entity_code'] = $entityCode;
    }

    public function getEntityCode()
    {
        return $this->entityCode;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        $this->apiParams['entity_name'] = $entityName;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function setIsvCode($isvCode)
    {
        $this->isvCode = $isvCode;
        $this->apiParams['isv_code'] = $isvCode;
    }

    public function getIsvCode()
    {
        return $this->isvCode;
    }

    public function setOrgCode($orgCode)
    {
        $this->orgCode = $orgCode;
        $this->apiParams['org_code'] = $orgCode;
    }

    public function getOrgCode()
    {
        return $this->orgCode;
    }
}
