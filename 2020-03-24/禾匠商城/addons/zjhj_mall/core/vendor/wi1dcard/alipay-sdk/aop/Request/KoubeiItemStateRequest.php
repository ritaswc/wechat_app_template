<?php
/**
 * ALIPAY API: koubei.item.state request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-21 11:05:00
 */

namespace Alipay\Request;

class KoubeiItemStateRequest extends AbstractAlipayRequest
{
    /**
     * 商品操作接口
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
