<?php
/**
 * ALIPAY API: koubei.item.extitem.update request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-06 10:48:31
 */

namespace Alipay\Request;

class KoubeiItemExtitemUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 商品修改接口
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
