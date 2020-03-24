<?php
/**
 * ALIPAY API: zhima.credit.pe.lawsuit.detail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-02 14:03:41
 */

namespace Alipay\Request;

class ZhimaCreditPeLawsuitDetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 个人涉诉详情接口
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
