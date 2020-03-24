<?php
/**
 * ALIPAY API: zhima.credit.ep.lawsuit.record.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-02 14:03:22
 */

namespace Alipay\Request;

class ZhimaCreditEpLawsuitRecordGetRequest extends AbstractAlipayRequest
{
    /**
     * 企业涉诉记录查询
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
