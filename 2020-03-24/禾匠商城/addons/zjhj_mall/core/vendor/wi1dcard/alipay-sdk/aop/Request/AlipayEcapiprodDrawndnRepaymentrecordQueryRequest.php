<?php
/**
 * ALIPAY API: alipay.ecapiprod.drawndn.repaymentrecord.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-29 11:34:40
 */

namespace Alipay\Request;

class AlipayEcapiprodDrawndnRepaymentrecordQueryRequest extends AbstractAlipayRequest
{
    /**
     * 支用编号
     **/
    private $drawndnNo;
    /**
     * 还款记录的终止时间，终止时间与起始时间的范围不能超过31天
     **/
    private $end;
    /**
     * 客户身份证号码，为18位，最后X必须为大写
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
    /**
     * 还款记录的起始时间（距离当前时间不能大于183天，只能在【0-183】之间）
     **/
    private $start;

    public function setDrawndnNo($drawndnNo)
    {
        $this->drawndnNo = $drawndnNo;
        $this->apiParams['drawndn_no'] = $drawndnNo;
    }

    public function getDrawndnNo()
    {
        return $this->drawndnNo;
    }

    public function setEnd($end)
    {
        $this->end = $end;
        $this->apiParams['end'] = $end;
    }

    public function getEnd()
    {
        return $this->end;
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

    public function setStart($start)
    {
        $this->start = $start;
        $this->apiParams['start'] = $start;
    }

    public function getStart()
    {
        return $this->start;
    }
}
