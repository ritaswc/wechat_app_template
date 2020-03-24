<?php
/**
 * ALIPAY API: koubei.craftsman.data.work.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-11 20:36:00
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataWorkCreateRequest extends AbstractAlipayRequest
{
    /**
     * 手艺人作品发布接口
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
