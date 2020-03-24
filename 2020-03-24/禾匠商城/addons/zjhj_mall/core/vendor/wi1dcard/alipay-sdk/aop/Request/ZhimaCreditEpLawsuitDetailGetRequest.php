<?php
/**
 * ALIPAY API: zhima.credit.ep.lawsuit.detail.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-13 14:43:31
 */

namespace Alipay\Request;

class ZhimaCreditEpLawsuitDetailGetRequest extends AbstractAlipayRequest
{
    /**
     * 企业涉诉详情查询
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
