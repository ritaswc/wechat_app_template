<?php
/**
 * ALIPAY API: koubei.craftsman.data.work.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-11 20:35:51
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataWorkModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改手艺人作品信息接口
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
