<?php
/**
 * ALIPAY API: koubei.retail.shopitem.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 18:08:34
 */

namespace Alipay\Request;

class KoubeiRetailShopitemModifyRequest extends AbstractAlipayRequest
{
    /**
     * isv 回传的门店商品信更新接口
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
