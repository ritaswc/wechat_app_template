<?php
/**
 * ALIPAY API: alipay.eco.cplife.bill.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:15:11
 */

namespace Alipay\Request;

class AlipayEcoCplifeBillModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改已上传的物业费账单数据
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
