<?php
/**
 * ALIPAY API: alipay.ins.auto.autoinsprod.common.consult request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 11:44:10
 */

namespace Alipay\Request;

class AlipayInsAutoAutoinsprodCommonConsultRequest extends AbstractAlipayRequest
{
    /**
     * 通用回调接口
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
