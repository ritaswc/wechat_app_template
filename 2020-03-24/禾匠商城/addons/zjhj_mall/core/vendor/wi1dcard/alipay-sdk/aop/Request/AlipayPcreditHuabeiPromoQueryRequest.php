<?php
/**
 * ALIPAY API: alipay.pcredit.huabei.promo.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-03 17:48:05
 */

namespace Alipay\Request;

class AlipayPcreditHuabeiPromoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 入参大字段
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
