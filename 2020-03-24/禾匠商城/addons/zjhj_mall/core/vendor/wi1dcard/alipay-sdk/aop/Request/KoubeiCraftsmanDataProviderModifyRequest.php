<?php
/**
 * ALIPAY API: koubei.craftsman.data.provider.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:17:29
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataProviderModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改手艺人信息接口
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
