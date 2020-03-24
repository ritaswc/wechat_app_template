<?php
/**
 * ALIPAY API: koubei.item.extitem.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-06 10:48:09
 */

namespace Alipay\Request;

class KoubeiItemExtitemBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 商品列表查询接口
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
