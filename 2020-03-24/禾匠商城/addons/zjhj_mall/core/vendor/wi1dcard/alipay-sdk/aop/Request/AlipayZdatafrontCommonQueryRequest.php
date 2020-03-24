<?php
/**
 * ALIPAY API: alipay.zdatafront.common.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-21 11:14:39
 */

namespace Alipay\Request;

class AlipayZdatafrontCommonQueryRequest extends AbstractAlipayRequest
{
    /**
     * 如果cacheInterval<=0,就直接从外部获取数据；
     * 如果cacheInterval>0,就先判断cache中的数据是否过期，如果没有过期就返回cache中的数据，如果过期再从外部获取数据并刷新cache，然后返回数据。
     * 单位：秒
     **/
    private $cacheInterval;
    /**
     * 通用查询的入参
     **/
    private $queryConditions;
    /**
     * 服务名称请与相关开发负责人联系
     **/
    private $serviceName;
    /**
     * 访问该服务的业务
     **/
    private $visitBiz;
    /**
     * 访问该服务的业务线
     **/
    private $visitBizLine;
    /**
     * 访问该服务的部门名称
     **/
    private $visitDomain;

    public function setCacheInterval($cacheInterval)
    {
        $this->cacheInterval = $cacheInterval;
        $this->apiParams['cache_interval'] = $cacheInterval;
    }

    public function getCacheInterval()
    {
        return $this->cacheInterval;
    }

    public function setQueryConditions($queryConditions)
    {
        $this->queryConditions = $queryConditions;
        $this->apiParams['query_conditions'] = $queryConditions;
    }

    public function getQueryConditions()
    {
        return $this->queryConditions;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        $this->apiParams['service_name'] = $serviceName;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function setVisitBiz($visitBiz)
    {
        $this->visitBiz = $visitBiz;
        $this->apiParams['visit_biz'] = $visitBiz;
    }

    public function getVisitBiz()
    {
        return $this->visitBiz;
    }

    public function setVisitBizLine($visitBizLine)
    {
        $this->visitBizLine = $visitBizLine;
        $this->apiParams['visit_biz_line'] = $visitBizLine;
    }

    public function getVisitBizLine()
    {
        return $this->visitBizLine;
    }

    public function setVisitDomain($visitDomain)
    {
        $this->visitDomain = $visitDomain;
        $this->apiParams['visit_domain'] = $visitDomain;
    }

    public function getVisitDomain()
    {
        return $this->visitDomain;
    }
}
