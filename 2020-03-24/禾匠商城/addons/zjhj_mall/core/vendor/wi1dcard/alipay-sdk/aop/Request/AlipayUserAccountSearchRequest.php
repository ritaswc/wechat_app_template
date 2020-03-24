<?php
/**
 * ALIPAY API: alipay.user.account.search request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-11 18:02:23
 */

namespace Alipay\Request;

class AlipayUserAccountSearchRequest extends AbstractAlipayRequest
{
    /**
     * 查询的结束时间
     **/
    private $endTime;
    /**
     * 需要过滤的字符
     **/
    private $fields;
    /**
     * 查询的页数
     **/
    private $pageNo;
    /**
     * 每页的条数
     **/
    private $pageSize;
    /**
     * 查询的开始时间
     **/
    private $startTime;
    /**
     * 查询账务的类型
     **/
    private $type;

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
        $this->apiParams['end_time'] = $endTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        $this->apiParams['fields'] = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
        $this->apiParams['page_no'] = $pageNo;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        $this->apiParams['page_size'] = $pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        $this->apiParams['start_time'] = $startTime;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setType($type)
    {
        $this->type = $type;
        $this->apiParams['type'] = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
