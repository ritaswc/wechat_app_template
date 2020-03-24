<?php
/**
 * ALIPAY API: alipay.offline.marketing.voucher.use request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 10:57:19
 */

namespace Alipay\Request;

class AlipayOfflineMarketingVoucherUseRequest extends AbstractAlipayRequest
{
    /**
     * 口碑外部券使用接口
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
