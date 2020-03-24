<?php
/**
 * ALIPAY API: koubei.retail.shopitem.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 18:19:47
 */

namespace Alipay\Request;

class KoubeiRetailShopitemBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * isv 回传的门店商品信息查询接口
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
