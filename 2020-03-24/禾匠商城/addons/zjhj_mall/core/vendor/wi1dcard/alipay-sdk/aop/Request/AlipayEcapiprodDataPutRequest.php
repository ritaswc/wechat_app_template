<?php
/**
 * ALIPAY API: alipay.ecapiprod.data.put request
 *
 * @author auto create
 *
 * @since  1.0, 2015-04-02 16:45:23
 */

namespace Alipay\Request;

class AlipayEcapiprodDataPutRequest extends AbstractAlipayRequest
{
    /**
     * 数据类型
     **/
    private $category;
    /**
     * 数据字符编码，默认UTF-8
     **/
    private $charSet;
    /**
     * 数据采集平台生成的采集任务编号
     **/
    private $collectingTaskId;
    /**
     * 身份证，工商注册号等
     **/
    private $entityCode;
    /**
     * 姓名或公司名等，name和code不能同时为空
     **/
    private $entityName;
    /**
     * 人或公司等
     **/
    private $entityType;
    /**
     * 渠道商
     **/
    private $isvCode;
    /**
     * 数据主体,以json格式传输的数据
     **/
    private $jsonData;
    /**
     * 数据合作方
     **/
    private $orgCode;

    public function setCategory($category)
    {
        $this->category = $category;
        $this->apiParams['category'] = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCharSet($charSet)
    {
        $this->charSet = $charSet;
        $this->apiParams['char_set'] = $charSet;
    }

    public function getCharSet()
    {
        return $this->charSet;
    }

    public function setCollectingTaskId($collectingTaskId)
    {
        $this->collectingTaskId = $collectingTaskId;
        $this->apiParams['collecting_task_id'] = $collectingTaskId;
    }

    public function getCollectingTaskId()
    {
        return $this->collectingTaskId;
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

    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
        $this->apiParams['entity_type'] = $entityType;
    }

    public function getEntityType()
    {
        return $this->entityType;
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

    public function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;
        $this->apiParams['json_data'] = $jsonData;
    }

    public function getJsonData()
    {
        return $this->jsonData;
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
