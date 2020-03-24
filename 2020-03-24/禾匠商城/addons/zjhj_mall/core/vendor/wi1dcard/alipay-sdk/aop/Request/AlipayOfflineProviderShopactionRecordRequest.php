<?php
/**
 * ALIPAY API: alipay.offline.provider.shopaction.record request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-23 22:32:27
 */

namespace Alipay\Request;

class AlipayOfflineProviderShopactionRecordRequest extends AbstractAlipayRequest
{
    /**
     * isv 回传的商户操作行为信息调用接口
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
