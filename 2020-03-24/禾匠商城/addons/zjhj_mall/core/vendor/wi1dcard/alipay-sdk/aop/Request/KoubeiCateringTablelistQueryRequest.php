<?php
/**
 * ALIPAY API: koubei.catering.tablelist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-26 14:14:12
 */

namespace Alipay\Request;

class KoubeiCateringTablelistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 门店id查桌码信息列表
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
