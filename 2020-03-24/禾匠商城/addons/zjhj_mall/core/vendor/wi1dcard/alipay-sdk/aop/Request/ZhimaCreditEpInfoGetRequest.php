<?php
/**
 * ALIPAY API: zhima.credit.ep.info.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-13 14:33:12
 */

namespace Alipay\Request;

class ZhimaCreditEpInfoGetRequest extends AbstractAlipayRequest
{
    /**
     * 企业工商综合信息查询
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
