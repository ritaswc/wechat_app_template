<?php
/**
 * ALIPAY API: alipay.marketing.card.activateurl.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-30 11:50:27
 */

namespace Alipay\Request;

class AlipayMarketingCardActivateurlApplyRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡领卡链接获取接口
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
