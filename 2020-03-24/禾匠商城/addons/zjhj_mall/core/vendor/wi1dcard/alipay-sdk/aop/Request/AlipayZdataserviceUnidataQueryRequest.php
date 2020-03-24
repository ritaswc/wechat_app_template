<?php
/**
 * ALIPAY API: alipay.zdataservice.unidata.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-26 16:20:03
 */

namespace Alipay\Request;

class AlipayZdataserviceUnidataQueryRequest extends AbstractAlipayRequest
{
    /**
     * 通用的查询入参
     **/
    private $queryCondition;
    /**
     * 返回数据的类型，内部业务系统分配
     **/
    private $uniqKey;

    public function setQueryCondition($queryCondition)
    {
        $this->queryCondition = $queryCondition;
        $this->apiParams['query_condition'] = $queryCondition;
    }

    public function getQueryCondition()
    {
        return $this->queryCondition;
    }

    public function setUniqKey($uniqKey)
    {
        $this->uniqKey = $uniqKey;
        $this->apiParams['uniq_key'] = $uniqKey;
    }

    public function getUniqKey()
    {
        return $this->uniqKey;
    }
}
