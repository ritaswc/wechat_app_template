<?php
/**
 * ALIPAY API: koubei.catering.tablecode.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-03 16:38:19
 */

namespace Alipay\Request;

class KoubeiCateringTablecodeQueryRequest extends AbstractAlipayRequest
{
    /**
     * 扫一扫查询桌码信息
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
