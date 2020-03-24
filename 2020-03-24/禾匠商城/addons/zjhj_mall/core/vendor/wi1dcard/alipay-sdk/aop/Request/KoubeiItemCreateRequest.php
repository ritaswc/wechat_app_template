<?php
/**
 * ALIPAY API: koubei.item.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-09 00:25:47
 */

namespace Alipay\Request;

class KoubeiItemCreateRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商品创建接口
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
