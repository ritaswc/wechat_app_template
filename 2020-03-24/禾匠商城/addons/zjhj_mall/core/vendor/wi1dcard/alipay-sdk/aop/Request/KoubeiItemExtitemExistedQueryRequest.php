<?php
/**
 * ALIPAY API: koubei.item.extitem.existed.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-06 10:48:15
 */

namespace Alipay\Request;

class KoubeiItemExtitemExistedQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询商品编码对应的商品是否存在
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
