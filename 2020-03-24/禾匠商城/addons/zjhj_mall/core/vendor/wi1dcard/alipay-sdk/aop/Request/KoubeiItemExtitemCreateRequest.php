<?php
/**
 * ALIPAY API: koubei.item.extitem.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-06 10:48:25
 */

namespace Alipay\Request;

class KoubeiItemExtitemCreateRequest extends AbstractAlipayRequest
{
    /**
     * 商品创建接口
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
