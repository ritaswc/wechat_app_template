<?php
/**
 * ALIPAY API: alipay.data.dataservice.shoppingmallrec.voucher.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-15 19:53:24
 */

namespace Alipay\Request;

class AlipayDataDataserviceShoppingmallrecVoucherQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商场券推荐
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
