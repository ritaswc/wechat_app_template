<?php
/**
 * ALIPAY API: alipay.trust.user.riskidentify.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-01-04 10:16:04
 */

namespace Alipay\Request;

class AlipayTrustUserRiskidentifyGetRequest extends AbstractAlipayRequest
{
    /**
     * 行业关注名单类型，具体类型见对接文档或样例代码
     **/
    private $type;

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
