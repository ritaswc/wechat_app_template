<?php
/**
 * ALIPAY API: koubei.item.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-09 14:44:09
 */

namespace Alipay\Request;

class KoubeiItemModifyRequest extends AbstractAlipayRequest
{
    /**
     * 商品修改
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
