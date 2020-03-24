<?php
/**
 * ALIPAY API: koubei.craftsman.data.provider.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-11 20:36:24
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataProviderCreateRequest extends AbstractAlipayRequest
{
    /**
     * 手艺人创建
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
