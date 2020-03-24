<?php
/**
 * ALIPAY API: alipay.offline.market.shop.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-19 16:55:33
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopCreateRequest extends AbstractAlipayRequest
{
    /**
     * 系统商需要通过该接口在口碑平台帮助商户创建门店信息。
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
