<?php
/**
 * ALIPAY API: alipay.offline.market.item.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-20 15:17:55
 */

namespace Alipay\Request;

class AlipayOfflineMarketItemCreateRequest extends AbstractAlipayRequest
{
    /**
     * 系统商需要通过该接口在口碑平台帮助商户创建商品。
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
